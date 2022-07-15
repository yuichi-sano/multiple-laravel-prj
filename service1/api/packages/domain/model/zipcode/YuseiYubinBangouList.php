<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use Ramsey\Collection\Collection;
use Traversable;

class YuseiYubinBangouList extends Collection
{
    /**
     * @return YuseiYubinBangou[]
     */
    public function __construct(array $list = null)
    {
        parent::__construct(YuseiYubinBangou::class);
        if (!empty($list)) {
            $this->setList($list);
        }
    }

    /**
     * @param array $list
     * @return void
     */
    private function setList(array $list)
    {
        foreach ($list as $item) {
            $this->add($item);
        }
    }

    /**
     * @return YuseiYubinBangou[]
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
        foreach ($this->data as $key => $data) {
            $toArray[$key] = $data;
        }
        return $toArray;
    }
}
