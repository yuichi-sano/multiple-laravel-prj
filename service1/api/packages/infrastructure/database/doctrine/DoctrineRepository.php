<?php

namespace packages\infrastructure\database\doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Illuminate\Support\Str;

class DoctrineRepository extends EntityRepository
{
    protected static string $collectionKeyName = 'collectionKeys';
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
     * @throws \ReflectionException
     */
    public function getSingleGroupingResult($rowDatas)
    {
        $transferAssociativeArray = $this->transferAssociativeArray($rowDatas);

        if(count($transferAssociativeArray) > 1){
                throw new \Exception('一件引きのはずが複数');
        }
        $reflect = new \ReflectionClass($this->getEntityName());
        $domain=$this->getClassMetadata()->newInstance();
        foreach ($transferAssociativeArray as $associateArray){
            $this->executeMapping($reflect,$associateArray,$domain);
        }
        return $domain;
    }

    /**
     * Collectionを伴うドメインモデルへ注入こちらは複数返却
     * @note many to one ,one to many, many to many を使う際のN+1問題の回避策
     * @param $results
     * @return object
     * @throws \ReflectionException
     */
    public function getGroupingResult($rowDatas)
    {
        $results = [];
        $transferAssociativeArray = $this->transferAssociativeArray($rowDatas);
        foreach ($transferAssociativeArray as $associateArray){
            $reflect = new \ReflectionClass($this->getEntityName());
            $domain=$this->getClassMetadata()->newInstance();
            $this->executeMapping($reflect, $associateArray, $domain);
            $results[] = $domain;
        }
        return $results;
    }

    /**
     * DBのrowデータをドメインモデルに準拠した連想配列に加工
     * @param $rowDatas
     * @return array
     * @throws \ReflectionException
     */
    protected function  transferAssociativeArray($rowDatas){
        $domainReflect = new \ReflectionClass($this->getEntityName());
        $groupResults = [];
        foreach ($rowDatas as $rowData){
            $collectionKeyStr = $this->getCollectionKeyStr($rowData,$domainReflect);
            $this->transferDomainModelAssociativeArray($rowData,$domainReflect, [], $groupResults[$collectionKeyStr]);
        }
        return $groupResults;
    }

    /**
     * 配列キーの生成
     * 配列キーはドメインモデルに設定されたプロパティに寄り決定されます
     * @param $rowData
     * @param \ReflectionClass $domainReflect
     * @param array $parentPrefixes
     * @return string
     * @throws \ReflectionException
     */
    protected function getCollectionKeyStr($rowData,\ReflectionClass $domainReflect, array $parentPrefixes=[]): string
    {
        $collectionKeys = $domainReflect->getProperty(self::$collectionKeyName)->getDefaultValue();
        $collectionKeyValues=[];
        $prefixes = $parentPrefixes;
        $prefixes[] = $this->getBaseClassName($domainReflect->getName());

        foreach ($collectionKeys as $collectionKey){
            $collectionKeyValues[] = $this->getColumnResult($rowData, $collectionKey, $prefixes);
        }

        return implode('_',$collectionKeyValues);
    }

    /**
     * 渡されたdomainモデルに従い連想配列へトランスします
     * @param $rowData
     * @param \ReflectionClass $domainReflect
     * @param array $parentPrefixes
     * @param $result
     * @return void
     * @throws \ReflectionException
     */
    protected function  transferDomainModelAssociativeArray($rowData,\ReflectionClass $domainReflect, array $parentPrefixes=[], &$result ){
        $prefixes = $parentPrefixes;
        $prefixes[] = $this->getBaseClassName($domainReflect->getName());
        foreach ($domainReflect->getProperties() as $property) {
            if (in_array($property->getName(), self::$ignoreProperties,true)) {
                continue;
            }
            if ($this->isCollectionObject($property->getType()->getName())) {
                $this->transferDomainModelAssociativeArrayRecursive($rowData, $property->getType()->getName(),  $prefixes, $result[Str::snake($property->getName())]);
            }elseif($property->getType()->isBuiltin() || $this->isBasicTypeObject($property->getType()->getName())){
                $result[Str::snake($property->getName())] = $this->getColumnResult($rowData, $property->getName(), $prefixes);
            }else{
                $childDomainReflect = new \ReflectionClass($property->getType()->getName());
                $this->transferDomainModelAssociativeArray($rowData, $childDomainReflect,  $prefixes, $result[Str::snake($property->getName())]);
            }
        }
    }

    /**
     * 配列型dmainModel
     * @param $rowData
     * @param $collectionClassName
     * @param array $parentPrefixes
     * @return array
     * @throws \ReflectionException
     */
    protected function transferDomainModelAssociativeArrayRecursive($rowData, $collectionClassName, array $parentPrefixes, &$result){
        $domainCollection = new ($collectionClassName);
        $domainReflect = new \ReflectionClass($domainCollection->getType());
        $prefixes = $parentPrefixes;
        $collectionKeyStr = $this->getCollectionKeyStr($rowData,$domainReflect,$prefixes);
        $this->transferDomainModelAssociativeArray($rowData, $domainReflect, $prefixes,$result[$collectionKeyStr]);
    }



    /**
     * オブジェクトマッピング
     * @param $reflect
     * @param $result
     * @param $domain
     * @return void
     * @throws \ReflectionException
     */
    private function executeMapping($reflect,$result, $domain){
        foreach ($reflect->getProperties() as $property){
            $reflectProp = new \ReflectionProperty(get_class($domain),$property->getName());
            $propType= $reflectProp->getType()->getName();
            if (in_array($property->getName(), self::$ignoreProperties,true)) {
                continue;
            }
            if(!class_exists($propType)){
                $this->getClassMetadata()->setFieldValue($domain,$property->getName(),$result[Str::snake($property->getName())]);
                continue;
            }
            if(!array_key_exists($property->getName(),$this->getClassMetadata()->reflFields)){
                $addProperty = (new RuntimeReflectionService())->getAccessibleProperty(get_class($domain), $property->getName());
                $this->getClassMetadata()->fieldMappings[]=[
                    "fieldName"=>$reflectProp->getName(),
                    "type"=>$propType,
                    'originalClass' => $reflectProp->getDeclaringClass()
                ];
                $this->getClassMetadata()->reflFields[$property->getName()]=$addProperty;
            }
            if($this->isCollectionObject($reflectProp->getType()->getName())){
                $collection = new (($reflectProp->getType())->getName());
                foreach ($result[Str::snake($reflectProp->getName())] as $item){
                    $collection->add($this->setChildProperty($collection->getType(),$item));
                }
                $addedObj=$collection;
                $this->getClassMetadata()->setFieldValue($domain,$property->getName(),$addedObj);
            }else{
                $addedObj = $this->setChildProperty($propType,$result);
                $this->getClassMetadata()->setFieldValue($domain,$property->getName(),$addedObj);
            }
        }
    }

    /**
     * 子オブジェクトマッピング
     * @param $className
     * @param $result
     * @return object
     */
    private function setChildProperty($className,$result){

        $class = $this->getEntityManager()->getRepository($className);
        $classMeta =$class->getClassMetadata();
        $instance=$classMeta->newInstance();
        $baseClassName=$this->getBaseClassName($className);
        if(count($classMeta->reflFields) == 1){
            $resultKey = Str::snake($baseClassName);
            $field = end($classMeta->reflFields);
            $classMeta->setFieldValue($instance,$field->getName(),$result);
            return $instance;
        }
        $reflect = new \ReflectionClass($className);
        foreach ($reflect->getProperties() as $property){
            $reflectProp = new \ReflectionProperty(get_class($instance),$property->getName());
            $propType= $reflectProp->getType()->getName();
            if (in_array($property->getName(), self::$ignoreProperties,true)) {
                continue;
            }
            if(!class_exists($propType)){
                $classMeta->setFieldValue($instance,$property->getName(),$result[Str::snake($property->getName())]);
                continue;
            }
            if(!array_key_exists($property->getName(),$classMeta->reflFields)){
                $addProperty = (new RuntimeReflectionService())->getAccessibleProperty(get_class($instance), $property->getName());
                $classMeta->fieldMappings[]=[
                    "fieldName"=>$reflectProp->getName(),
                    "type"=>$propType,
                    'originalClass' => $reflectProp->getDeclaringClass()
                ];
                $classMeta->reflFields[$property->getName()]=$addProperty;
            }

            if($this->isCollectionObject($reflectProp->getType()->getName())){
                $collection = new (($reflectProp->getType())->getName());
                foreach ($result[Str::snake($reflectProp->getName())] as $item){

                    $collection->add($this->setChildProperty($collection->getType(),$item));
                }
                $addedObj=$collection;
                $classMeta->setFieldValue($instance,$property->getName(),$addedObj);
            }elseif($this->isBasicTypeObject($reflectProp->getType()->getName())){
                $addedObj = $this->setChildProperty($reflectProp->getType()->getName(),$result[Str::snake($property->getName())]);
                $classMeta->setFieldValue($instance,$property->getName(),$addedObj);
            }else {
                $addedObj = $this->setChildProperty($propType,$result[Str::snake($property->getName())]);
                $classMeta->setFieldValue($instance,$property->getName(),$addedObj);
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
    protected function isCollectionObject($className){
        if(!class_exists($className)){
            return false;
        }
        if(!get_parent_class($className)){
            return false;
        }
        return in_array($this->getBaseClassName(get_parent_class($className)),self::$baseCollectionClassNames,true);
    }
    /**
     * DoctrineRepositoryにおいて認めているCollectionクラスを継承しているか精査
     * @param $className
     * @return bool
     */
    protected function isBasicTypeObject($className){
        if(!class_exists($className)){
            return false;
        }
        if(!$interfaces =class_implements($className)){
            return false;
        }

        foreach ($interfaces as $interface){
            if(str_contains($interface, self::$basicTypeNameSpace)){
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
    private function getColumnResult($result, $key, array $prefixes){

        array_shift($prefixes);

        $withPrefixKey ='';
        if($prefixes){
            $withPrefixKey = Str::snake(implode('', $prefixes).'_'.$key);
        }

        if(array_key_exists($withPrefixKey,$result)){
            return $result[$withPrefixKey];
        }else{
            return $result[Str::snake($key)];
        }
    }





}
