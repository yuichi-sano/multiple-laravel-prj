<?php
namespace packages\service;
use packages\domain\model\User\UserId;
use packages\domain\model\User\User;
use packages\infrastructure\database\UserRepository;

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
