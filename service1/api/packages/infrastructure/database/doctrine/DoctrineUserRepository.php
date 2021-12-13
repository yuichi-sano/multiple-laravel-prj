<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use packages\domain\model\User\User;
use packages\domain\model\User\UserId;
use packages\infrastructure\database\UserRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findUser(UserId $userId): User
    {
        $query = $this->createNativeNamedQuery('ddd-sample');
        try {
            return $query->getSingleResult();
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
