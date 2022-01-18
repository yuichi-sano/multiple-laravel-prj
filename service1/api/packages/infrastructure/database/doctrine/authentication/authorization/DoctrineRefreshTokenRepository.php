<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\authentication\authorization;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use packages\domain\model\authentication\authorization\AuthenticationRefreshToken;
use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\authentication\authorization\RefreshTokenRepository;
use packages\domain\model\User\User;
use packages\domain\model\User\UserId;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineRefreshTokenRepository extends DoctrineRepository implements RefreshTokenRepository
{

    public function findByToken(RefreshToken $refreshToken): AuthenticationRefreshToken
    {
        $query = $this->createNativeNamedQuery('findByToken');
        try {
            $query->setParameters([$refreshToken->toString()]);
            return  $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }

    }
    public function save(AuthenticationRefreshToken  $authenticationRefreshToken): void
    {
        $query = $this->createNativeNamedQuery('upsert');
        try {
            $query->setParameters($authenticationRefreshToken->toArray());
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
