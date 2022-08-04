<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\prefecture;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use packages\domain\model\prefecture\Prefecture;
use packages\domain\model\prefecture\PrefectureCode;
use packages\domain\model\prefecture\PrefectureList;
use packages\domain\model\prefecture\PrefectureName;
use packages\domain\model\prefecture\PrefectureRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrinePrefectureRepository extends DoctrineRepository implements PrefectureRepository
{
    protected function getParentDir(): string
    {
        return parent_dir(dirname(__FILE__));
    }

    public function list(): PrefectureList
    {
        $sql = $this->readNativeQueryFile('prefectureList');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        try {
            return new PrefectureList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function findByCode(PrefectureCode $prefectureCode): Prefecture
    {
        $sql = $this->readNativeQueryFile('findPrefecture');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'prefectureCode' => $prefectureCode->getValue(),
        ]);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function findByName(PrefectureName $prefectureName): Prefecture
    {
        $sql = $this->readNativeQueryFile('findPrefectureByName');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'prefectureName' => $prefectureName->getValue(),
        ]);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
