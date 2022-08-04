<?php

namespace App\Extension\Hasher;

use Illuminate\Contracts\Hashing\Hasher;

class MD5Hasher implements Hasher
{
    public function info($hashedValue)
    {
        return $hashedValue;
    }

    public function make($value, array $options = []): string
    {
        return md5($value);
    }

    public function check($value, $hashedValue, array $options = []): bool
    {
        return (md5($value) == $hashedValue);
    }

    public function needsRehash($hashedValue, array $options = []): bool
    {
        return false;
    }
}
