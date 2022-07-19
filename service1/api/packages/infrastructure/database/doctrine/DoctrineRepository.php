<?php

namespace packages\infrastructure\database\doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;


abstract class DoctrineRepository extends EntityRepository
{
    protected static string $collectionKeyName = 'collectionKeys';

    abstract protected function getParentDir(): string;

    protected static array $ignoreProperties = [
        'collectionKeys'
    ];
    protected static array $baseCollectionClassNames = [
        'Collection'
    ];
    protected static string $basicTypeNameSpace = 'packages\domain\basic\type';

    /**
     * Collectionを伴うドメインモデルへ注入
     * @note many to one ,one to many, many to many を使う際のN+1問題の回避策
     * @param $results
     * @return object
     * @throws ReflectionException
     */
    public function getSingleGroupingResult($rowDatas)
    {
        $transferAssociativeArray = $this->transferAssociativeArray($rowDatas);
        if (count($transferAssociativeArray) > 1) {
            throw new Exception('一件引きのはずが複数');
        }
        $reflect = new ReflectionClass($this->getEntityName());
        $domain = $this->getClassMetadata()->newInstance();
        foreach ($transferAssociativeArray as $associateArray) {
            $this->executeMapping($reflect, $associateArray, $domain);
        }
        return $domain;
    }

    /**
     * Collectionを伴うドメインモデルへ注入こちらは複数返却
     * @note many to one ,one to many, many to many を使う際のN+1問題の回避策
     * @param $results
     * @return object
     * @throws ReflectionException
     */
    public function getGroupingResult($rowDatas)
    {
        $results = [];
        $transferAssociativeArray = $this->transferAssociativeArray($rowDatas);
        foreach ($transferAssociativeArray as $associateArray) {
            $reflect = new ReflectionClass($this->getEntityName());
            $domain = $this->getClassMetadata()->newInstance();
            $this->executeMapping($reflect, $associateArray, $domain);
            $results[] = $domain;
        }
        return $results;
    }

    /**
     * DBのrowデータをドメインモデルに準拠した連想配列に加工
     * @param $rowDatas
     * @return array
     * @throws ReflectionException
     */
    protected function transferAssociativeArray($rowDatas): array
    {
        $domainReflect = new ReflectionClass($this->getEntityName());
        $groupResults = [];
        foreach ($rowDatas as $rowData) {
            $collectionKeyStr = $this->getCollectionKeyStr($rowData, $domainReflect);
            $this->transferDomainModelAssociativeArray($rowData, $domainReflect, $groupResults[$collectionKeyStr], []);
        }

        return $groupResults;
    }

    /**
     * 配列キーの生成
     * 配列キーはドメインモデルに設定されたプロパティに寄り決定されます
     * @param $rowData
     * @param ReflectionClass $domainReflect
     * @param array $parentPrefixes
     * @return string
     * @throws ReflectionException
     */
    protected function getCollectionKeyStr($rowData, ReflectionClass $domainReflect, array $parentPrefixes = []): string
    {
        $collectionKeys = $domainReflect->getProperty(self::$collectionKeyName)->getDefaultValue();
        $collectionKeyValues = [];
        $prefixes = $parentPrefixes;
        $prefixes[] = $this->getBaseClassName($domainReflect->getName());

        foreach ($collectionKeys as $collectionKey) {
            $collectionKeyValues[] = $this->getColumnResult($rowData, $collectionKey, $prefixes);
        }

        return implode('_', $collectionKeyValues);
    }

    /**
     * 渡されたdomainモデルに従い連想配列へトランスします
     * FIXME オブジェクト入れ子になっていて、互いに参照しあう場合、無限ループに陥る可能性があるので、再帰する深さを制限する必要がある。
     * @param $rowData
     * @param ReflectionClass $domainReflect
     * @param array $parentPrefixes
     * @param $result
     * @return void
     * @throws ReflectionException
     */
    protected function transferDomainModelAssociativeArray(
        $rowData,
        ReflectionClass $domainReflect,
        &$result,
        array $parentPrefixes = []
    ): void {
        $prefixes = $parentPrefixes;
        $prefixes[] = $this->getBaseClassName($domainReflect->getName());
        foreach ($domainReflect->getProperties() as $property) {
            if (in_array($property->getName(), self::$ignoreProperties, true)) {
                continue;
            }
            if ($this->isCollectionObject($property->getType()->getName())) {
                $this->transferDomainModelAssociativeArrayRecursive(
                    $rowData,
                    $property->getType()->getName(),
                    $result[Str::snake($property->getName())],
                    $prefixes
                );
            } elseif ($property->getType()->isBuiltin() || $this->isBasicTypeObject($property->getType()->getName())) {
                $result[Str::snake($property->getName())] = $this->getColumnResult(
                    $rowData,
                    $property->getName(),
                    $prefixes
                );
            } else {
                $childDomainReflect = new ReflectionClass($property->getType()->getName());
                $this->transferDomainModelAssociativeArray(
                    $rowData,
                    $childDomainReflect,
                    $result[Str::snake($property->getName())],
                    $prefixes
                );
            }
        }
    }

    /**
     * 配列型dmainModel
     * @param $rowData
     * @param $collectionClassName
     * @param array $parentPrefixes
     * @throws ReflectionException
     */
    protected function transferDomainModelAssociativeArrayRecursive(
        $rowData,
        $collectionClassName,
        &$result,
        array $parentPrefixes
    ) {
        $domainCollection = new $collectionClassName();
        $domainReflect = new ReflectionClass($domainCollection->getType());
        $prefixes = $parentPrefixes;
        $collectionKeyStr = $this->getCollectionKeyStr($rowData, $domainReflect, $prefixes);
        $this->transferDomainModelAssociativeArray($rowData, $domainReflect, $result[$collectionKeyStr], $prefixes);
    }

    /**
     * オブジェクトマッピング
     * @param $reflect
     * @param $result
     * @param $domain
     * @return void
     * @throws ReflectionException
     */
    private function executeMapping($reflect, $result, $domain)
    {
        foreach ($reflect->getProperties() as $property) {
            $reflectProp = new ReflectionProperty(get_class($domain), $property->getName());
            $propType = $reflectProp->getType()->getName();
            if (in_array($property->getName(), self::$ignoreProperties, true)) {
                continue;
            }
            if (!class_exists($propType)) {
                $this->getClassMetadata()->setFieldValue(
                    $domain,
                    $property->getName(),
                    $result[Str::snake($property->getName())]
                );
                continue;
            }
            if (!array_key_exists($property->getName(), $this->getClassMetadata()->reflFields)) {
                $addProperty = (new RuntimeReflectionService())->getAccessibleProperty(
                    get_class($domain),
                    $property->getName()
                );
                $this->getClassMetadata()->fieldMappings[] = [
                    "fieldName" => $reflectProp->getName(),
                    "type" => $propType,
                    'originalClass' => $reflectProp->getDeclaringClass()
                ];
                $this->getClassMetadata()->reflFields[$property->getName()] = $addProperty;
            }
            if ($this->isCollectionObject($reflectProp->getType()->getName())) {
                $collectionClassName = $reflectProp->getType()->getName();
                $collection = new $collectionClassName();
                foreach ($result[Str::snake($reflectProp->getName())] as $item) {
                    $collection->add(
                        $this->setChildProperty(
                            $collection->getType(),
                            $item,
                            true,
                            $property->getName()
                        )
                    );
                }
                $addedObj = $collection;
                $this->getClassMetadata()->setFieldValue($domain, $property->getName(), $addedObj);
            } else {
                $addedObj = $this->setChildProperty($propType, $result, false, $property->getName());
                $this->getClassMetadata()->setFieldValue($domain, $property->getName(), $addedObj);
            }
        }
    }

    /**
     * 子オブジェクトマッピング
     * @param $className
     * @param $result
     * @return object
     */
    private function setChildProperty($className, $result, $isCollectionChild = false, $propertyName = null)
    {
        $class = $this->getEntityManager()->getRepository($className);
        $classMeta = $class->getClassMetadata();
        $instance = $classMeta->newInstance();
        $baseClassName = $this->getBaseClassName($className);
        if (count($classMeta->reflFields) == 1) {
            $resultKey = Str::snake($baseClassName);
            $field = end($classMeta->reflFields);
            if (array_key_exists($resultKey, $result)) {
                $classMeta->setFieldValue($instance, $field->getName(), $result[$resultKey]);
            } else {
                $classMeta->setFieldValue($instance, $field->getName(), $result[Str::snake($propertyName)]);
            }

            return $instance;
        }
        $reflect = new ReflectionClass($className);
        foreach ($reflect->getProperties() as $property) {
            $reflectProp = new ReflectionProperty(get_class($instance), $property->getName());
            $propType = $reflectProp->getType()->getName();
            if (in_array($property->getName(), self::$ignoreProperties, true)) {
                continue;
            }
            if (!class_exists($propType)) {
                $classMeta->setFieldValue($instance, $property->getName(), $result[Str::snake($property->getName())]);
                continue;
            }
            if (!array_key_exists($property->getName(), $classMeta->reflFields)) {
                $addProperty = (new RuntimeReflectionService())->getAccessibleProperty(
                    get_class($instance),
                    $property->getName()
                );
                $classMeta->fieldMappings[] = [
                    "fieldName" => $reflectProp->getName(),
                    "type" => $propType,
                    'originalClass' => $reflectProp->getDeclaringClass()
                ];
                $classMeta->reflFields[$property->getName()] = $addProperty;
            }

            if ($this->isCollectionObject($reflectProp->getType()->getName())) {
                $collectionClassName = $reflectProp->getType()->getName();
                $collection = new $collectionClassName();
                foreach ($result[Str::snake($reflectProp->getName())] as $item) {
                    $collection->add(
                        $this->setChildProperty(
                            $collection->getType(),
                            $item,
                            true,
                            $property->getName()
                        )
                    );
                }
                $addedObj = $collection;
                $classMeta->setFieldValue($instance, $property->getName(), $addedObj);
            } elseif ($this->isBasicTypeObject($reflectProp->getType()->getName())) {
                if ($isCollectionChild) {
                    $addedObj = $this->setChildProperty(
                        $reflectProp->getType()->getName(),
                        $result,
                        false,
                        $property->getName()
                    );
                    $classMeta->setFieldValue($instance, $property->getName(), $addedObj);
                } else {
                    $addedObj = $this->setChildProperty(
                        $reflectProp->getType()->getName(),
                        $result[Str::snake($propertyName)],
                        false,
                        $property->getName()
                    );
                    $classMeta->setFieldValue($instance, $property->getName(), $addedObj);
                }
            } else {
                if (array_key_exists(Str::snake($propertyName), $result)) {
                    $addedObj = $this->setChildProperty(
                        $propType,
                        $result[Str::snake($propertyName)],
                        false,
                        $property->getName()
                    );
                    $classMeta->setFieldValue($instance, $property->getName(), $addedObj);
                }
            }
        }
        return $instance;
    }

    /**
     * Namespaceを外したクラス名取得
     * @param $className
     * @return false|string
     */
    protected function getBaseClassName($className)
    {
        $classParts = explode('\\', $className);
        return end($classParts);
    }

    /**
     * DoctrineRepositoryにおいて認めているCollectionクラスを継承しているか精査
     * @param $className
     * @return bool
     */
    protected function isCollectionObject($className): bool
    {
        if (!class_exists($className)) {
            return false;
        }
        if (!get_parent_class($className)) {
            return false;
        }
        return in_array($this->getBaseClassName(get_parent_class($className)), self::$baseCollectionClassNames, true);
    }

    /**
     * DoctrineRepositoryにおいて認めているCollectionクラスを継承しているか精査
     * @param $className
     * @return bool
     */
    protected function isBasicTypeObject($className): bool
    {
        if (!class_exists($className)) {
            return false;
        }
        if (!$interfaces = class_implements($className)) {
            return false;
        }

        foreach ($interfaces as $interface) {
            if (str_contains($interface, self::$basicTypeNameSpace)) {
                return true;
            }
        }
        return false;
    }

    /**
     * SQLRAWデータからカラム名で取得
     * @param $result
     * @param $key
     * @param array $prefixes 親のオブジェクト名(親の親まで再帰的に保持
     * @return mixed
     */
    private function getColumnResult($result, $key, array $prefixes)
    {
        array_shift($prefixes);

        $withPrefixKey = '';
        if ($prefixes) {
            $withPrefixKey = Str::snake(implode('', $prefixes) . '_' . $key);
        }

        if (array_key_exists($withPrefixKey, $result)) {
            return $result[$withPrefixKey];
        } elseif (array_key_exists(Str::snake($key), $result)) {
            return $result[Str::snake($key)];
        }
    }

    /**
     * NativeQueryの読み込み
     * @param $queryName
     * @param $bladeParams
     * @return string
     * @throws BindingResolutionException
     */
    public function readNativeQueryFile(string $queryName, array $bladeParams = []): string
    {
        $app = app();
        $view = view();
        $orgFinder = $view->getFinder();
        $sqlPath = native_query_path($this->getParentDir());
        $newFinder = new FileViewFinder($app['files'], [$sqlPath]);
        $view->setFinder($newFinder);
        $view->addExtension('sql', 'blade');
        $obj = $view->make($queryName, $bladeParams);
        $result = $obj->render();
        $view->setFinder($orgFinder);
        return $result;
    }

    /**
     * DataMigrationファイルの読み込み
     * @param $migrationName
     * @param $bladeParams
     * @return string
     * @throws BindingResolutionException
     */
    public function readMigrationFile(string $migrationName, array $bladeParams = []): string
    {
        $app = app();
        $view = view();
        $orgFinder = $view->getFinder();
        $sqlPath = data_migrations_path();
        $newFinder = new FileViewFinder($app['files'], [$sqlPath]);
        $view->setFinder($newFinder);
        $view->addExtension('sql', 'blade');
        $obj = $view->make($migrationName, $bladeParams);
        $result = $obj->render();
        $view->setFinder($orgFinder);
        return $result;
    }

    public function getDefaultRSM(): ResultSetMappingBuilder
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        return $rsm->addNamedNativeQueryResultClassMapping($this->getClassMetadata(), $this->getClassName());
    }
}
