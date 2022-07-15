<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\merchant;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;
use packages\domain\model\user\User;
use packages\domain\model\user\UserRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineMerchantRepository extends DoctrineRepository implements MerchantRepository
{
    protected function getParentDir(): string
    {
        return parent_dir(dirname(__FILE__));
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findMerchant(int $merchantId): Merchant
    {
        //$query = $this->createNativeNamedQuery('ddd-sample');
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $sql = $this->readNativeQueryFile('ddd-sample');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        try {
            return $this->getSingleGroupingResult($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function list(): array
    {
        $query = $this->createNativeNamedQuery('ddd-sample');
        try {
            return $this->getGroupingResult($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
