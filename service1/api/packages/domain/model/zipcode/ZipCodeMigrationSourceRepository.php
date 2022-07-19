<?php

namespace packages\domain\model\zipcode;

interface ZipCodeMigrationSourceRepository
{

    public function yuseiYubinBangouPut(ZipCodeList $zipCodeList);

    public function yuseiLargeBusinessYubinBangouPut(YuseiLargeBusinessYubinBangouList $list);

    public function yuseiYubinBangouDiff(): ZipCodeSourceDiffList;

    public function yuseiLargeBusinessYubinBangouDiff(): ZipCodeSourceDiffList;
}
