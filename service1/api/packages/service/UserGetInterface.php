<?php

namespace packages\service;

use packages\domain\model\user\UserId;
use packages\domain\model\user\User;

interface UserGetInterface
{
    /**
     * @param UserId $userId
     * @return User
     */
    public function execute(UserId $userId): User;
}
