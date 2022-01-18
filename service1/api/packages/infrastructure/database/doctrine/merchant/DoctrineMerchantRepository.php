<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\merchant;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;
use packages\domain\model\User\User;
use packages\domain\model\User\UserId;
use packages\domain\model\User\UserRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineMerchantRepository extends DoctrineRepository implements MerchantRepository
{

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findMerchant(int $merchantId): Merchant
    {
        $query = $this->createNativeNamedQuery('ddd-sample');
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
