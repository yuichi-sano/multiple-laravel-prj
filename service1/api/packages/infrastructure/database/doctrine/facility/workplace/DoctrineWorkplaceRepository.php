<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\facility\workplace;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use packages\domain\model\facility\workplace\WorkplaceList;
use packages\domain\model\facility\workplace\WorkplaceRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineWorkplaceRepository extends DoctrineRepository implements WorkplaceRepository
{
    protected function getParentDir(): string
    {
        return implode(
            '/',
            array_diff(
                explode('/', dirname(__FILE__)),
                explode('/', doctrine_repo_path())
            )
        );
    }

    /**
     * @return WorkplaceList
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function list(): WorkplaceList
    {
        $sql = $this->readNativeQueryFile('findListAll');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getGroupingRsm());
        try {
            return new WorkplaceList($this->getGroupingResult($query->getResult()));
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * job_work_placeにたいして複数のdelivery_work_placeが紐づく
     * @return ResultSetMapping
     */
    private function getGroupingRsm(): ResultSetMapping
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('job_work_place_id', 'workplace_id');
        $rsm->addScalarResult('job_work_place_name', 'workplace_name');
        $rsm->addScalarResult('meishou1', 'delivery_workplace_company_name');
        $rsm->addScalarResult('meishou2', 'delivery_workplace_name');
        $rsm->addScalarResult('bumon_code', 'delivery_workplace_code');
        $rsm->addScalarResult('bumon_code_s', 'delivery_workplace_code_s');
        return $rsm;
    }
}
