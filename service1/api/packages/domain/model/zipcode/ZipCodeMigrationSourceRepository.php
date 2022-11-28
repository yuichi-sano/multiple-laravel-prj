<?php

namespace packages\domain\model\zipcode;

interface ZipCodeMigrationSourceRepository
{
    public function zipPut(ZipCodeList $zipCodeList);

    public function yuseiYubinBangouPut(ZipCodeList $zipCodeList);

    public function yuseiLargeBusinessYubinBangouPut(YuseiLargeBusinessYubinBangouList $list);

    public function zipDiff(): ZipCodeSourceDiffList;

    public function yuseiYubinBangouDiff(): ZipCodeSourceDiffList;

    public function yuseiLargeBusinessYubinBangouDiff(): ZipCodeSourceDiffList;
}
