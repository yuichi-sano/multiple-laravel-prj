<?php

namespace Tests\Unit\packages\domain\model\zipcode;

use packages\domain\model\zipcode\ZipCodePostalCode;
use PHPUnit\Framework\TestCase;

/**
 * ZipCodeドメインない郵便番号オブジェクトテスト
 */
class ZipCodePostalCodeTest extends TestCase
{
    // function名、比較用変数の値は仮

    /**
     * 空判定テスト
     * @return void
     */
    public function test_isEmpty() {
        $target = new ZipCodePostalCode();

        $this->assertTrue($target->isEmpty());

        $target = new ZipCodePostalCode('0000000');
        $this->assertFalse($target->isEmpty());

        $this->assertFalse($target->isMostOld());
        $this->assertFalse($target->isOld());

    }

    public function test_format() {
        $target = new ZipCodePostalCode('0000000');
        $this->assertSame($target->format(), '000-0000');
    }

    public function test_toMostOldStr(){
        $target = new ZipCodePostalCode('0000000');
        $this->assertSame($target->toMostOldStr(), '000');
    }

    public function test_isOld() {
        $target = new ZipCodePostalCode();

        $target = new ZipCodePostalCode('0000000');
        $this->assertFalse($target->isOld());
        $target = new ZipCodePostalCode('000');
        $this->assertFalse($target->isOld());

        $target = new ZipCodePostalCode('00000');
        $this->assertTrue($target->isOld());

    }

    public function test_isMostOld() {
        $target = new ZipCodePostalCode();

        $target = new ZipCodePostalCode('0000000');
        $this->assertFalse($target->isMostOld());
        $target = new ZipCodePostalCode('00000');
        $this->assertFalse($target->isMostOld());

        $target = new ZipCodePostalCode('000');
        $this->assertTrue($target->isMostOld());

    }





}
