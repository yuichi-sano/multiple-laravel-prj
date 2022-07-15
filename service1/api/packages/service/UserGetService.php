<?php

namespace packages\service;

use packages\domain\model\user\UserId;
use packages\domain\model\user\User;
use packages\domain\model\user\UserRepository;

class UserGetService implements UserGetInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserId $userId): User
    {
        return $this->userRepository->findUser(new UserId(1));
    }
}
