<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use Ramsey\Collection\Collection;
use SplFileObject;
use Traversable;

class ZipCodeList extends Collection
{
    /**
     * @return ZipCode[]
     */
    public function __construct(array $zipCodeList = null)
    {
        parent::__construct(ZipCode::class);
        if (!empty($zipCodeList)) {
            $this->setList($zipCodeList);
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
     * @return ZipCode[]
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

    /**
     * 郵政提供Csvデータから自身を生成する
     * @FIXME Factoryにすべきな気もする
     * @param SplFileObject $file
     * @return ZipCodeList
     */
    public static function createForCsv(SplFileObject $file): ZipCodeList
    {
        $instance = new self();
        $needContinue = false;
        $mergeRows = [];
        $id = 1;
        foreach ($file as $key => $row) {
            //mb_convert_variables('UTF-8', 'sjis-win', $row);
            $mergeRows[] = $row;
            $factory = new ZipCodeFactory();
            $isUnClose = $factory->isUnClose($row);
            $isClose = $factory->isClose($row);
            if (($isUnClose || $needContinue) && !$isClose) {
                $needContinue = true;
                continue;
            }
            if ($isClose) {
                $needContinue = false;
            }
            $mergedRow = $factory->mergeRows($mergeRows);
            if ($factory->needSplit($mergedRow)) {
                foreach ($factory->splitRow($mergedRow) as $splitRow) {
                    $zipCode = $factory->create($id, $splitRow);
                    $instance->add($zipCode);
                    $id++;
                }
            } else {
                $zipCode = $factory->create($id, $mergedRow);
                $townArea = $zipCode->getTownArea();
                $townAreaKana = $zipCode->getTownAreaKana();
                $zipCode->setTownAreaKana(
                    $factory->cleanCantSplitTownAreaKana($townArea, $townAreaKana)
                );
                $zipCode->setTownArea($factory->cleanCantSplitTownArea($townArea));
                $instance->add($zipCode);
                $id++;
            }
            $mergeRows = [];
        }
        return $instance;
    }
}
