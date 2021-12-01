<?php

namespace App\Extension\Auth\Provider;

use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class ExtensionDoctrineUserProvider extends DoctrineUserProvider
{
    public function setHasher(HasherContract $hasher){
        $this->hasher = $hasher;
    }
}
