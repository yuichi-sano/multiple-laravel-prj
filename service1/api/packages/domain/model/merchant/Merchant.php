<?php

declare(strict_types=1);

namespace packages\domain\model\merchant;

use packages\domain\model\user\UserList;

class Merchant
{
    private int $merchantId;
    private string $password;
    private string $name;
    private string $tel;
    private string $mail;
    private string $zip;
    private string $prefCode;
    private string $address;
    private UserList $userList;
    public array $collectionKeys = [
        'merchantId'
    ];

    /**
     * @return UserList
     */
    public function getUserList(): UserList
    {
        return $this->userList;
    }
}
