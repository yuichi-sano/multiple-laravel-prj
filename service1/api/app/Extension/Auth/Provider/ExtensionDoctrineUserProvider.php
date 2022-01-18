<?php

namespace App\Extension\Auth\Provider;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use packages\domain\model\authentication\Account;
use packages\domain\model\authentication\authorization\RefreshToken;

class ExtensionDoctrineUserProvider extends DoctrineUserProvider
{
    public function setHasher(HasherContract $hasher){
        $this->hasher = $hasher;
    }
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByToken(RefreshToken $refreshToken): Account
    {
        $query = $this->getRepository()->createNativeNamedQuery('findByToken');
        $query->setParameter(1, $refreshToken->toString());
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }

    }
}
