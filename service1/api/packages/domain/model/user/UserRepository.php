<?php

namespace packages\domain\model\user;

interface UserRepository
{
    public function findUser(UserId $userId): User;

    public function add(User $user): void;
}
