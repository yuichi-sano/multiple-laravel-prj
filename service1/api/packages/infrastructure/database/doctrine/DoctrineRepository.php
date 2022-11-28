<?php

namespace packages\infrastructure\database\doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\ReflectionEmbeddedProperty;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Doctrine\Persistence\Reflection\TypedNoDefaultReflectionProperty;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;
use Ramsey\Collection\Collection;
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
     * @param ClassMetadata $classMetadata
     * @param array $rowDataList
     * @param string $columnPrefix
     * @param string|null $parentKey
     * @return Array<object>
     * @throws Exception
     */
    public function getResultDomainList(ClassMetadata $classMetadata, array $rowDataList, string $columnPrefix = '', string $parentKey = null): array
    {
        $EntityDomainList = [];
        foreach($rowDataList as $rowData){
            $key = $this->getCollectionKeyValueStr($classMetadata, $rowData ,$columnPrefix);
            $entityDomain = $this->getResultDomain($classMetadata, $rowData, $rowDataList, $columnPrefix, null, $key);
            if($parentKey){
                $EntityDomainList[$parentKey][$key] = $entityDomain;
            }else{
                $EntityDomainList[$key] = $entityDomain;
            }
        }
        return $EntityDomainList;
    }

    /**
     * classMetaの情報からドメインモデルを生成します
     * @note 自身を再帰的に呼び出します。
     * @param ClassMetadata $classMetadata 対象ドメインのメタデータ
     * @param array $rowData 処理対象rowデータ（一次元,一行）
     * @param string $columnPrefix
     * @param mixed|null $columnResult この引数が指定されている場合、rowDataのからは取得しません
     * @param string|null $key 配列キー
     * @param array $rowDataList 処理対象rowデータ（一次元,複数行）
     * @return object
     * @throws Exception
     */
    public function getResultDomain(
        ClassMetadata $classMetadata, array $rowData,array $rowDataList,
        string $columnPrefix = '', mixed $columnResult = null,
        string $key = null,
    ): object {
        $entityDomain = $classMetadata->newInstance();
        foreach($classMetadata->fieldMappings as $map) {
            $col = $columnPrefix.$map['columnName'];
            $columnData = $columnResult ?: isset($rowData[$col]) ?? null;
            if (isset($map['declaredField'])) {
                $fieldNameFirst = current($this->splitFieldNames($map['fieldName']));
                if(!is_null($classMetadata->getFieldValue($entityDomain, $fieldNameFirst))){
                    continue;
                }
                $childName = $this->getFieldClassName($classMetadata, $fieldNameFirst);
                $childMeta = $this->getEntityManager()->getRepository($childName)->getClassMetadata();
                $field = current($this->splitFieldNames($map['declaredField']));
                $data = $this->getResultDomain($childMeta, $rowData, $rowDataList, $columnPrefix, $columnData, $key );
            }else{
                $field = $map['fieldName'];
                $data = $columnData;
            }
            $classMetadata->setFieldValue($entityDomain, $field, $data);
        }

        foreach ($classMetadata->getAssociationMappings() as $associationMapping){
            if($associationMapping['inversedBy']){
                continue;
            }
            $associationDomain = $this->getAssociationResultDomain($classMetadata, $associationMapping, $rowDataList);
            $classMetadata->setFieldValue(
                $entityDomain,
                $associationMapping['fieldName'],
                $associationDomain
            );
        }
        return $entityDomain;
    }

    /**
     * @param ClassMetadata $classMetadata
     * @param $associationMapping
     * @param $rowDataList
     * @return Collection
     * @throws Exception
     */
    public function getAssociationResultDomain(ClassMetadata $classMetadata, $associationMapping, $rowDataList): Collection
    {
        $assocClassMetadata = $this->getEntityManager()->getRepository(
            $associationMapping['targetEntity']
        )->getClassMetadata();
        $assocDomainDataList = $this->getResultDomainList($assocClassMetadata, $rowDataList, $associationMapping['mappedBy'].'_', $key);
        $assocDomainClassName = $this->getFieldClassName($classMetadata, $associationMapping['fieldName']);
        $associationDomain = new ($assocDomainClassName)($assocDomainDataList[$key]);
        if(!isset($associationDomain)){
            throw new \Exception('コレクションオブジェクトが定義されていません');
        }
        return $associationDomain;
    }

    /**
     * @param ClassMetadata $classMetadata
     * @param string $fieldName
     * @return string|void
     */
    private function getFieldClassName(ClassMetadata $classMetadata, string $fieldName){
        $propertyType = $classMetadata->getReflectionProperty($fieldName)->getType();
        if(method_exists($propertyType,'getName')){
            return $propertyType->getName();
        }
        foreach ($propertyType->getTypes() as $type){
            if($this->isCollectionObject($type->getName())){
                return $type->getName();
            };
        }
    }


    /**
     * フィールド名をピリオドで区切り配列に変換
     * @param $fieldName
     * @return array
     */
    private function splitFieldNames($fieldName): array
    {
        return explode('.', $fieldName);
    }

    /**
     * ドメインをコレクションする際の配列キーを生成
     * @param $classMetadata
     * @param array $rowData
     * @param $columnPrefix
     * @return string
     */
    public function getCollectionKeyValueStr($classMetadata, array $rowData, $columnPrefix = ''): string
    {
        $collectionKeyColumns = $classMetadata->getIdentifierColumnNames();
        $collectionKeyValues = [];
        foreach($collectionKeyColumns as $collectionKeyColumn){
            $collectionKeyValues[] = $rowData[$columnPrefix . $collectionKeyColumn] ?? null;
        }
        return implode('_',$collectionKeyValues);
    }

    /**
     * @param $rowDatas
     * @return object[]
     */
    public function getResultByNativeQuery($rowDatas): array
    {
        try {
            return $this->getResultDomainList($this->getClassMetadata(), $rowDatas);
        } catch (Exception $e) {
        }
    }

    /**
     * @param $rowDatas
     * @return object|bool
     * @throws Exception
     */
    public function getSingleResultByNativeQuery($rowDatas): object|bool
    {
        $result = $this->getResultDomainLis($rowDatas);
        if(count($result) > 1){
            throw new \Exception('一件引きのはずが複数件ヒットしています');
        }
        return current($result);
    }


    /**
     * Collectionを伴うドメインモデルへ注入
     * @note many to one ,one to many, many to many を使う際のN+1問題の回避策
     * @param array $rowDatas
     * @return object $domain
     * @throws ReflectionException|ErrorException
     */
    public function getSingleGroupingResult(array $rowDatas): object
    {
        $transferAssociativeArray = $this->transferAssociativeArray($rowDatas);
        if (count($transferAssociativeArray) > 1) {
            throw new Exception('一件引きのはずが複数レコード取得されています');
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
     * @param array $rowDatas
     * @return object[] $results
     * @throws ReflectionException|ErrorException
     */
    public function getGroupingResult($rowDatas): array
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
     * @param array $rowDatas
     * @return array $groupResults
     * @throws ReflectionException
     */
    protected function transferAssociativeArray(array $rowDatas): array
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
        $prefixes = $this->mergePrefixes($this->getBaseClassName($domainReflect->getName()), $parentPrefixes);

        foreach ($collectionKeys as $collectionKey) {
            $collectionKeyValues[] = $this->getColumnResult($rowData, $collectionKey, $prefixes);
        }

        return implode('_', $collectionKeyValues);
    }

    /**
     * 渡されたdomainモデルに従い連想配列へトランスします
     * この関数は再帰的に呼び出されます。
     * FIXME オブジェクト入れ子になっていて、互いに参照しあう場合、無限ループに陥る可能性があるので、エラー防止のため再帰する深さを制限する必要がある。
     * @param array $rowData SQLから取得された生データ(必ず１次元配列)
     * @param ReflectionClass $domainReflect ドメインクラスをリフレクションで再現したクラス
     * @param array $parentPrefixes 処理対象のドメインモデルを包含する親ドメインモデルがある場合、親から見た時のメンバ変数名が渡されます。階層が深くなるほど配列も深くなります。
     * @param mixed $result transfer結果変数に対して本関数内で参照渡しにて更新していきます。
     * @return void
     * @throws ReflectionException
     */
    protected function transferDomainModelAssociativeArray(
        array $rowData,
        ReflectionClass $domainReflect,
        &$result,
        array $parentPrefixes = []
    ): void {
        $prefixes = $this->mergePrefixes($this->getBaseClassName($domainReflect->getName()), $parentPrefixes);
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
     * 配列型domainModel
     * @param array $rowData
     * @param string $collectionClassName
     * @param array $result
     * @param array $parentPrefixes
     * @throws ReflectionException
     */
    protected function transferDomainModelAssociativeArrayRecursive(
        array $rowData,
        string $collectionClassName,
        mixed &$result,
        array $parentPrefixes
    ) {
        $domainCollection = new $collectionClassName();
        $domainReflect = new ReflectionClass($domainCollection->getType());
        $collectionKeyStr = $this->getCollectionKeyStr($rowData, $domainReflect, $parentPrefixes);
        $this->transferDomainModelAssociativeArray($rowData, $domainReflect, $result[$collectionKeyStr], $parentPrefixes);
    }

    /**
     * オブジェクトマッピング
     * @param ReflectionClass $domainReflect
     * @param array $transferedArrayData 処理対象クラスが必要とするデータ
     * @param object $domain マッピングさせる対象であるドメインモデル
     * @return void
     * @throws ErrorException
     * @throws ReflectionException
     */
    private function executeMapping(ReflectionClass $domainReflect, array $transferedArrayData, object $domain): void
    {
        foreach ($domainReflect->getProperties() as $property) {
            try {
                $this->setFieldValueForProperty(
                    $this->getClassMetadata(),
                    $property,
                    $domain,
                    $transferedArrayData
                );
            } catch (ErrorException $e) {
                throw new ErrorException($e->getMessage() . "\n" . "処理対象ドメイン:" . $domainReflect->getName() . "\n処理対象プロパティ:" . $property);
            }
        }
    }

    /**
     * 子オブジェクトマッピング
     * @param $className
     * @param $transferedArrayData
     * @param bool $isCollectionChild
     * @param null $propertyName
     * @return object
     * @throws ErrorException
     * @throws ReflectionException
     * @throws MappingException
     */
    private function setChildProperty($className, $transferedArrayData, $isCollectionChild = false, $propertyName = null): object
    {
        $class = $this->getEntityManager()->getRepository($className);
        $classMeta = $class->getClassMetadata();
        $instance = $classMeta->newInstance();


        $baseClassName = $this->getBaseClassName($className);
        if (count($classMeta->reflFields) == 1) {
            $resultKey = Str::snake($baseClassName);
            $field = end($classMeta->reflFields);
            if (array_key_exists($resultKey, $transferedArrayData)) {
                $value = $transferedArrayData[$resultKey];
            } else {
                $value = $transferedArrayData[Str::snake($propertyName)];
                if ($this->isBasicTypeObject($className)) {
                    if ($classMeta->getFieldMapping('value')['type'] == 'datetime') {
                        $value = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($value)));
                    };
                };
            }
            $classMeta->setFieldValue($instance, $field->getName(), $value);
            return $instance;
        }
        $reflect = new ReflectionClass($className);
        foreach ($reflect->getProperties() as $property) {
            try {
                $this->setFieldValueForProperty(
                    $classMeta,
                    $property,
                    $instance,
                    $transferedArrayData,
                    $isCollectionChild,
                    $propertyName
                );
            } catch (ErrorException $e) {
                throw new \ErrorException($e->getMessage() . "\n" . "処理対象ドメイン:" . $className . "\n処理対象プロパティ:" . $property);
            }
        }
        return $instance;
    }

    /**
     * @param ClassMetadata $classMeta
     * @param ReflectionProperty $property
     * @param object $domain
     * @param array $transferedArrayData
     * @param bool $isCollectionChild
     * @param null $propertyName
     * @return void
     * @throws ErrorException
     * @throws MappingException
     * @caution
     * あるドメインのメンバ変数が持つ子要素ドメインに注入出来なかった場合<br>
     * MappingExceptionをキャッチしますが、処理は続行します。<br>
     * これは抽象クラスなどをメンバ変数に設定する必要がある場合,インスタンス化出来なく、自動で判別するわけにもいかずという事態になるため。
     * この手のメンバ変数への注入は別の手段にてデータ取得してください
     * @throws ReflectionException
     */
    private function setFieldValueForProperty(ClassMetadata $classMeta, ReflectionProperty $property, object $domain, array $transferedArrayData, $isCollectionChild = false, $propertyName = null): void
    {
        $reflectProp = new ReflectionProperty(get_class($domain), $property->getName());
        $propType = $reflectProp->getType()->getName();
        $baseClassName = $this->getBaseClassName($classMeta->getName());
        if (in_array($property->getName(), self::$ignoreProperties, true)) {
            return;
        }
        if (!class_exists($propType)) {
            if ($isCollectionChild || $propertyName == '') {
                $classMeta->setFieldValue($domain, $property->getName(), $transferedArrayData[Str::snake($property->getName())]);
            } else {
                $classMeta->setFieldValue($domain, $property->getName(), $transferedArrayData[Str::snake($baseClassName)][Str::snake($property->getName())]);
            }
            return;
        }
        if (!array_key_exists($property->getName(), $classMeta->reflFields)) {
            $addProperty = (new RuntimeReflectionService())->getAccessibleProperty(
                get_class($domain),
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
            if(array_key_exists(Str::snake($reflectProp->getName()), $transferedArrayData)) {
                foreach ($transferedArrayData[Str::snake($reflectProp->getName())] as $item) {
                    try {
                        $collection->add(
                            $this->setChildProperty(
                                $collection->getType(),
                                $item,
                                true,
                                $property->getName()
                            )
                        );
                    } catch (\Doctrine\Persistence\Mapping\MappingException $e) {
                        //TODO LoggerでMapping出来なかった旨を吐き出す
                        //@caution 子要素ドメインに注入出来なかった場合は処理を続行します
                    }
                }
            }
            $addedObj = $collection;
            $classMeta->setFieldValue($domain, $property->getName(), $addedObj);
        } elseif ($this->isBasicTypeObject($propType)) {
            $data = $transferedArrayData;
            if (array_key_exists(Str::snake($propertyName), $transferedArrayData)) {
                $data = $transferedArrayData[Str::snake($propertyName)];
            }
            if ($isCollectionChild) {
                $data = $transferedArrayData;
            }
            $addedObj = $this->setChildProperty(
                $propType,
                $data,
                false,
                $property->getName()
            );
            $classMeta->setFieldValue($domain, $property->getName(), $addedObj);
        } else {

            if (!array_key_exists(Str::snake($propertyName), $transferedArrayData)) {
                $data = $transferedArrayData;
            } else {
                $data = $transferedArrayData[Str::snake($propertyName)];
            }
            try {
                $addedObj = $this->setChildProperty(
                    $propType,
                    $data,
                    false,
                    $property->getName()
                );
                $classMeta->setFieldValue($domain, $property->getName(), $addedObj);
            } catch (\Doctrine\Persistence\Mapping\MappingException $e) {
                //TODO LOGでマッピングできなかった旨を吐き出す
                //@caution 子要素ドメインに注入出来なかった場合は処理を続行します
                var_dump($e);
            }
        }
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
     * DomainのBasicなinterfaceを実装したクラスであるか（IntegerType等など）
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
     * @param array $rowData DBから取得した生データ 必ず1次元配列
     * @param string $key domainModelないに指定されているcollectionKeyの一要素
     * @param array $prefixes 親のオブジェクト名(親の親まで再帰的に保持
     * @return mixed $rowDataから任意のキー項目の値を抜き出す。つまり取得したデータの一カラムにある情報
     */
    private function getColumnResult(array $rowData, string $key, array $prefixes)
    {
        array_shift($prefixes);
        $withPrefixKey = '';
        if ($prefixes) {
            $withPrefixKey = Str::snake(implode('', $prefixes) . '_' . $key);
        }
        if (array_key_exists($withPrefixKey, $rowData)) {
            return $rowData[$withPrefixKey];
        } elseif (array_key_exists(Str::snake($key), $rowData)) {
            return $rowData[Str::snake($key)];
        }
        //throw new DatabaseObjectNotFoundException('取得データから指定のキー要素は見つかりませんでした');
    }

    /**
     * prefixをマージ
     * @param string $prefix
     * @param array $parentPrefixes
     * @return array
     */
    private function mergePrefixes(string $prefix, array $parentPrefixes = []): array
    {
        $prefixes = $parentPrefixes;
        $prefixes[] = $prefix;
        return $prefixes;
    }

    /**
     * NativeQueryの読み込み
     * @param string $queryName
     * @param array $bladeParams
     * @return string
     * @throws BindingResolutionException
     */
    public function readNativeQueryFile(string $queryName, array $bladeParams = []): string
    {
        return $this->readSqlFile($queryName, native_query_path($this->getParentDir()), $bladeParams);
    }

    /**
     * DataMigrationファイルの読み込み
     * @param string $migrationName
     * @param array $bladeParams
     * @return string
     * @throws BindingResolutionException
     */
    public function readMigrationFile(string $migrationName, array $bladeParams = []): string
    {
        return $this->readSqlFile($migrationName, data_migrations_path(), $bladeParams);
    }

    /**
     * @throws BindingResolutionException
     */
    public function readSqlFile(string $name, string $path, array $bladeParams = []): string
    {
        $app = app();
        $view = view();
        $orgFinder = $view->getFinder();
        $newFinder = new FileViewFinder($app['files'], [$path]);
        $view->setFinder($newFinder);
        $view->addExtension('sql', 'blade');
        $obj = $view->make($name, $bladeParams);
        $result = $obj->render();
        $view->setFinder($orgFinder);
        return $result;
    }


    /**
     * @return ResultSetMappingBuilder
     */
    public function getDefaultRSM(): ResultSetMappingBuilder
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        return $rsm->addNamedNativeQueryResultClassMapping($this->getClassMetadata(), $this->getClassName());
    }
}
