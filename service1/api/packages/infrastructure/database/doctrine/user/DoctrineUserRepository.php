<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\user;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use packages\domain\model\user\User;
use packages\domain\model\user\UserId;
use packages\domain\model\user\UserRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    protected function getParentDir(): string
    {
        return parent_dir(dirname(__FILE__));
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findUser(UserId $userId): User
    {
        $query = $this->createNativeNamedQuery('ddd-sample');
        $query->setParameters([
            'user_id' => $userId->toInteger()
        ]);
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
