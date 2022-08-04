<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use Ramsey\Collection\Collection;
use SplFileObject;
use Traversable;

class YuseiLargeBusinessYubinBangouList extends Collection
{
    /**
     * @return YuseiLargeBusinessYubinBangou[]
     */
    public function __construct(array $list = null)
    {
        parent::__construct(YuseiLargeBusinessYubinBangou::class);
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
     * @return YuseiLargeBusinessYubinBangou[]
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }

    /**
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

    public static function createForCSV(SplFileObject $file): self
    {
        $instance = new self();
        foreach ($file as $key => $row) {
            $largeBusinessYubinBangou = new YuseiLargeBusinessYubinBangou(
                new ZipCodeJis($row[0]),
                new ZipCodeOldPostalCode($row[8]),
                new ZipCodePostalCode($row[7]),
                $row[2],
                $row[1],
                $row[3],
                $row[4],
                $row[5],
                $row[6],
                $row[9],
                $row[10],
                $row[11],
                $row[12],
            );

            $instance->add($largeBusinessYubinBangou);
        }

        return $instance;
    }
}
