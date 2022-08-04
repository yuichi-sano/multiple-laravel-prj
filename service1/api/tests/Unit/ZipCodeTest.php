<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ZipCodeTest extends TestCase
{
    // function名、比較用変数の値は仮

    /**
     * 郵政郵便番号前回更新情報成功時レスポンステスト
     *
     * @return void
     */
    public function test_LastUpdateInfo() {
        //  前回一括更新日 (string*)
        $bulk_update_date = '';
        //  前回一括更新者 (string*)
        $bulk_user = '';
        //  前回個別登録日 (string)
        $add_date = '';
        //  前回個別登録者 (string*)
        $user = '';
        //  前回個別登録郵便番号 (string*)
        $zip_code = '';
        //  前回個別登録都道府県 (string*)
        $kenmei = '';
        //  前回個別登録都道府県コード (integer*)
        $ken_code = 0;
        //  前回個別登録市区町村 (string*)
        $sikumei = '';
        //  前回個別登録市区町村コード (integer*)
        $siku_code = 0;

        // TODO 取得処理呼び出し

        //  前回一括更新日
        //  $this->assertSame($bulk_update_date, $response->bulk_update_date);
        //  前回一括更新者
        //  $this->assertSame($bulk__user, $response->bulk__user);
        //  前回個別登録日
        //  $this->assertSame($add_date, $response->add_date);
        //  前回個別登録者
        //  $this->assertSame($_user, $response->_user);
        //  前回個別登録郵便番号
        //  $this->assertSame($zip_code, $response->zip_code);
        //  前回個別登録都道府県
        //  $this->assertSame($kenmei, $response->kenmei);
        //  前回個別登録都道府県コード
        //  $this->assertSame($ken_code, $response->ken_code);
        //  前回個別登録市区町村
        //  $this->assertSame($sikumei, $response->sikumei);
        //  前回個別登録市区町村コード
        //  $this->assertSame($siku_code, $response->siku_code);
    }


    /**
     * 郵政郵便番号情報取得テスト
     *
     * @return void
     */
    public function test_getZipCodeInfo() {
        //  件数の差分 (integer*)
        $difference_number = 0;
        //  更新郵便番号 (string*)
        $zip_code = '';
        //  都道府県コード (integer*)
        $ken_code = 0;
        //  市区町村 (string*)
        $sikumei = '';
        //  市区町村コード (integer)
        $siku_code = 0;

        // TODO 取得処理呼び出し

        //  件数の差分
        //  $this->assertSame($difference_number, $response->difference_number);
        //  更新郵便番号
        //  $this->assertSame($zip_code, $response->zip_code);
        //  都道府県コード
        //  $this->assertSame($ken_code, $response->ken_code);
        //  市区町村
        //  $this->assertSame($sikumei, $response->sikumei);
        //  市区町村コード
        //  $this->assertSame($siku_code, $response->siku_code);
    }


    /**
     * 郵便番号検索テスト
     *
     * @return void
     */
    public function test_searchZipCode() {
        //  管理ID (integer*)
        $id = 0;
        //  更新郵便番号 (string*)
        $zip_code = '';
        //  都道府県 (string*)
        $kenmei = '';
        //  都道府県コード (integer*)
        $ken_code = 0;
        //  市区町村 (string*)
        $sikumei = '';
        //  市区町村コード (integer*)
        $siku_code = 0;

        // TODO 取得処理呼び出し

        //  管理ID
        //  $this->assertSame($id, $response->id);
        //  更新郵便番号
        //  $this->assertSame($zip_code, $response->zip_code);
        //  都道府県
        //  $this->assertSame($kenmei, $response->kenmei);
        //  都道府県コード
        //  $this->assertSame($ken_code, $response->ken_code);
        //  市区町村
        //  $this->assertSame($sikumei, $response->sikumei);
        //  市区町村コード
        //  $this->assertSame($siku_code, $response->siku_code);
    }


    /**
     * 都道府県データ取得テスト
     *
     * @return void
     */
    public function test_getPrefectureData() {
        //  都道府県ID (string*)
        $prefecture_id = '';
        //  都道府県名 (string*)
        $prefecture_name = '';

        // TODO 取得処理呼び出し

        //  都道府県ID
        //  $this->assertSame($prefecture_id, $response->prefecture_id);
        //  都道府県名
        //  $this->assertSame($prefecture_name, $response->prefecture_name);
    }


}
