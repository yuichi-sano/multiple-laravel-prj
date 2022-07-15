<?php

declare(strict_types=1);

namespace packages\domain\model\user;

use Ramsey\Collection\Collection;
use Traversable;

class Addresses extends Collection
{
    /**
     * @return Address[]
     */
    public function __construct()
    {
        parent::__construct(Address::class);
    }

    /**
     * @return Address[]
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }

    /**
     * 連想配列にコンバートします
     * @return array
     */
    public function toArray(): array
    {
        $toArray = [];
        foreach ($this->data as $data) {
            $toArray[$data->personal] = $data->address;
        }
        return $toArray;
    }
}
