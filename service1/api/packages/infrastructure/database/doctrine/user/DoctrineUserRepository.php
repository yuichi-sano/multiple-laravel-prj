<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\user;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use packages\domain\model\User\User;
use packages\domain\model\User\UserId;
use packages\domain\model\User\UserRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findUser(UserId $userId): User
    {
        $query = $this->createNativeNamedQuery('ddd-sample');
        try {
            return $this->getSingleGroupingResult($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }


    }

    public function add(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
