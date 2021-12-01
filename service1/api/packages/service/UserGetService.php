<?php
namespace packages\Service;
use packages\Domain\Model\User\UserId;
use packages\Domain\Model\User\User;
use packages\Infrastructure\Database\UserRepository;

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
