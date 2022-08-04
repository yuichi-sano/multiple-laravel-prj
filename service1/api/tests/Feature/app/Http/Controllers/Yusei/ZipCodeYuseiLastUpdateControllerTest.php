<?php

namespace Tests\Feature\app\Http\Controllers\Yusei;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZipCodeYuseiLastUpdateControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index()
    {
        $reason = '郵政郵便番号マスタメンテナンスのmigration系処理のFeatureテストは実行時負荷等を考慮し実装しない方針';
        $this->markTestIncomplete($reason);
//        $response = $this->get('/zip_code/yusei/last_update',[
//            'Authorization' => 'Bearer ' . $this->createAccessToken()
//        ]);
//
//        $response->assertStatus(200);
    }
}
