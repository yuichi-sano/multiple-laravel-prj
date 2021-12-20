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

    /**
     * Collectionを伴うドメインモデルへ注入
     * @note many to one ,one to many, many to many を使う際のN+1問題の回避策
     * @param $results
     * @return object
     * @throws \ReflectionException
     */
    public function getSingleGroupingResult($results)
    {

        $reflect = new \ReflectionClass($this->getEntityName());
        $collectionKeys = $reflect->getProperty(self::$collectionKeyName)->getDefaultValue();

        $groupResults = [];
        foreach ($results as $result){
            $collectionKeyValues = [];
            foreach ($collectionKeys as $collectionKey){
                $collectionKeyValues[] = $result[Str::snake($collectionKey)];
            }
            $collectionKeyStr = implode('_',$collectionKeyValues);

            foreach ($reflect->getProperties() as $property) {
                if (in_array($property->getName(), self::$ignoreProperties,true)) {
                    continue;
                }
                if(!$this->isCollectionObject($property->getType()->getName())){
                    //if ($property->getType()->isBuiltin()) {
                    $groupResults[$collectionKeyStr][Str::snake($property->getName())] = $result[Str::snake($property->getName())];
                } elseif ($this->isCollectionObject($property->getType()->getName())) {
                    $arrayObjs = new ($property->getType()->getName());

                    $arrayObj = new \ReflectionClass($arrayObjs->getType());
                    foreach ($arrayObj->getProperties() as $prop) {
                        $dd[Str::snake($prop->getName())] = $result[Str::snake($prop->getName())];
                    }
                    $groupResults[$collectionKeyStr][Str::snake($property->getName())][] = $dd;
                }

            }

        }

        foreach ($groupResults as $result){

            $reflect = new \ReflectionClass($this->getEntityName());
            $domain=$this->getClassMetadata()->newInstance();
            $this->setProperty($reflect,$result,$domain);

        }
        return $domain;
    }

    /**
     * オブジェクトマッピング
     * @param $reflect
     * @param $result
     * @param $domain
     * @return void
     * @throws \ReflectionException
     */
    private function setProperty($reflect,$result, $domain){

        foreach ($reflect->getProperties() as $property){
            $reflectProp = new \ReflectionProperty($this->getEntityName(),$property->getName());
            $propType= $reflectProp->getType()->getName();

            if (in_array($property->getName(), self::$ignoreProperties,true)) {
                continue;
            }
            if(!class_exists($propType)){
                $this->getClassMetadata()->setFieldValue($domain,$property->getName(),$result[Str::snake($property->getName())]);
                continue;
            }
            if(!array_key_exists($property->getName(),$this->getClassMetadata()->reflFields)){
                $addProperty = (new RuntimeReflectionService())->getAccessibleProperty($this->getEntityName(), $property->getName());
                $this->getClassMetadata()->fieldMappings[]=[
                    "fieldName"=>$reflectProp->getName(),
                    "type"=>$propType,
                    'originalClass' => $reflectProp->getDeclaringClass()
                ];
                $this->getClassMetadata()->reflFields[$property->getName()]=$addProperty;
            }

            if($this->isCollectionObject($reflectProp->getType()->getName())){
                $collection = new (($reflectProp->getType())->getName());
                foreach ($result[$reflectProp->getName()] as $item){
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
            $classMeta->setFieldValue($instance,$field->getName(),$result[$resultKey]);
            return $instance;
        }
        foreach ($classMeta->reflFields as $field){
            $classMeta->setFieldValue($instance,$field->getName(),$result[Str::snake($field->getName())]);
        }
        return $instance;
    }

    /**
     * Namespaceを外したクラス名取得
     * @param $className
     * @return false|string
     */
    public function getBaseClassName($className)
    {
        $classParts = explode('\\', $className);
        return end($classParts);
    }

    /**
     * DoctrineRepositoryにおいて認めているCollectionクラスを継承しているか精査
     * @param $className
     * @return bool
     */
    public function isCollectionObject($className){
        if(!class_exists($className)){
            return false;
        }
        if(!get_parent_class($className)){
            return false;
        }
        return in_array($this->getBaseClassName(get_parent_class($className)),self::$baseCollectionClassNames,true);
    }
}
