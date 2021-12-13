<?php

declare(strict_types=1);
namespace packages\domain\model\User;
use Ramsey\Collection\Collection;

class UserList extends Collection
{
    /**
     * @return User[]
     */
    public function __construct()
    {
        parent::__construct(\packages\domain\model\User\User::class);
    }

    /**
     * @return Task[]
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator(); // TODO: Change the autogenerated stub
    }
}
