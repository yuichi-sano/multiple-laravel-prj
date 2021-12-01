<?php
namespace packages\Service;
use packages\Domain\Model\User\UserId;
use packages\Domain\Model\User\User;
interface UserGetInterface
{
/**
* @param UserId $userId
* @return User
*/
public function execute(UserId $userId): User;
}
