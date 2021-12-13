<?php
namespace packages\infrastructure\database;
use packages\domain\model\User\User;
use packages\domain\model\User\UserId;
interface UserRepository {
	public function findUser(UserId $userId): User;
	public function add(User $user): void;
}
