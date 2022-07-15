<?php

namespace Tests\Feature\app\Http\Controllers\Yusei;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZipCodeYuseiSearchControllerTest extends TestCase
{
    /**
     * 取得処理
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get("/zip_code/yusei/search?zipCode=1000011",
            [
                'Authorization' => 'Bearer '.$this->createAccessToken()
            ]
        );

//        $response->dump();
        $response->assertStatus(200);
    }

    /**
     * 取得処理（データなし）
     *
     * @return void
     */
    public function test_index_no_result()
    {
        $response = $this->get("/zip_code/yusei/search?zipCode=1000000",
            [
                'Authorization' => 'Bearer '.$this->createAccessToken()
            ]
        );

//        $response->dump()
//            ->assertStatus(204)
//            ->assertJson([
//                'state' => 'E_204_0000001',
//                'message' => 'お探しの郵便番号は見つかりませんでした。',
//            ])
//        ;
        $response->assertStatus(204);
    }

}
