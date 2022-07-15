<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\authentication\authorization;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use packages\domain\model\authentication\authorization\AuthenticationRefreshToken;
use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\authentication\authorization\RefreshTokenRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineRefreshTokenRepository extends DoctrineRepository implements RefreshTokenRepository
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

    public function findByToken(RefreshToken $refreshToken): AuthenticationRefreshToken
    {
        $sql = $this->readNativeQueryFile('findByToken');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameter('refreshToken', $refreshToken->toString());
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function save(AuthenticationRefreshToken $authenticationRefreshToken): void
    {
        $sql = $this->readNativeQueryFile('upsert');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters($authenticationRefreshToken->toArray());
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
