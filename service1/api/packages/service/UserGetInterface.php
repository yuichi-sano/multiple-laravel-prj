<?php
namespace packages\service;
use packages\domain\model\User\UserId;
use packages\domain\model\User\User;
interface UserGetInterface
{
/**
* @param UserId $userId
* @return User
*/
public function execute(UserId $userId): User;
}
