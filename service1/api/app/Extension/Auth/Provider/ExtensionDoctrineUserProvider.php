<?php

namespace App\Extension\Auth\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;
use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use packages\domain\model\authentication\Account;
use packages\domain\model\authentication\authorization\RefreshToken;

class ExtensionDoctrineUserProvider extends DoctrineUserProvider
{

    public function setHasher(HasherContract $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByToken(RefreshToken $refreshToken): Account
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

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function retrieveById(mixed $identifier): Account
    {
        $sql = $this->readNativeQueryFile('findByUserId');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameter('userId', $identifier);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    private function getParentDir()
    {
        return 'authentication/';
    }

    /**
     * NativeQueryの読み込み
     * @param $queryName
     * @param $bladeParams
     * @return string
     * @throws BindingResolutionException
     */
    private function readNativeQueryFile(string $queryName, array $bladeParams = []): string
    {
        $app = app();
        $view = view();
        $orgFinder = $view->getFinder();
        $sqlPath = native_query_path($this->getParentDir());
        $newFinder = new FileViewFinder($app['files'], [$sqlPath]);
        $view->setFinder($newFinder);
        $view->addExtension('sql', 'blade');
        $obj = $view->make($queryName, $bladeParams);
        $result = $obj->render();
        $view->setFinder($orgFinder);
        return $result;
    }

    private function getDefaultRSM(): ResultSetMappingBuilder
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        return $rsm->addNamedNativeQueryResultClassMapping($this->getClassMetadata(), $this->getClassName());
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    /**
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    protected function getClassMetadata(): \Doctrine\ORM\Mapping\ClassMetadata
    {
        return $this->em->getClassMetadata($this->entity);
    }

    /**
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->entity;
    }
}
