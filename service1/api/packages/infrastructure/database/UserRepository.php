<?php
namespace packages\Infrastructure\Database;
use packages\Domain\Model\User\User;
use packages\Domain\Model\User\UserId;
interface UserRepository {
	public function findUser(UserId $userId): User;
	public function add(User $user): void;
}
