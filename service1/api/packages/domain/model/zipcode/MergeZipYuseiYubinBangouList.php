<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use Ramsey\Collection\Collection;
use Traversable;

class MergeZipYuseiYubinBangouList extends Collection
{
    /**
     * @return MergeZipYuseiYubinBangou[]
     */
    public function __construct(array $list = null)
    {
        parent::__construct(MergeZipYuseiYubinBangou::class);
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
     * @return MergeZipYuseiYubinBangou[]
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }

    public function toArray(): array
    {
        return parent::toArray();
    }
}
