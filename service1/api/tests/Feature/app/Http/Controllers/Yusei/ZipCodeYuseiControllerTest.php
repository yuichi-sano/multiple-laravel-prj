<?php

namespace Tests\Feature\app\Http\Controllers\Yusei;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZipCodeYuseiControllerTest extends TestCase
{
    /**
     * 登録処理
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->post("/zip_code/yusei", [
            'zipCode' => '1000013',
            'kenmei' => '東京都',
            'kenmeiKana' => 'ﾄｳｷｮｳﾄ',
            'kenCode' => 13,
            'sikumei' => '千代田区',
            'sikumeiKana' => 'ﾁﾖﾀﾞｸ',
            'sikuCode' => 13101,
            'town' => '内幸町その２',
            'townKana' => 'ｳﾁｻｲﾜｲﾁｮｳｿﾉﾆ',
        ],
            [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

        $response->assertStatus(200);
    }

    /**
     * 登録テスト（必須エラー）
     *
     * @return void
     */
    public function test_store_error_required()
    {
        $response = $this->post("/zip_code/yusei", [],

            [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

        $response->dump()
            ->assertStatus(400)
            ->assertJson([
                'state' => 'V_0000000',
                'message' => 'バリデーションエラー',
                'result' => [
                    "zipCode" => [
                        0 => "郵便番号は、必ず指定してください。"
                    ],
                    "kenmei" => [
                        0 => "都道府県名は、必ず指定してください。"
                    ],
                    "kenmeiKana" => [
                        0 => "都道府県カナは、必ず指定してください。"
                    ],
                    "kenCode" => [
                        0 => "都道府県コードは、必ず指定してください。"
                    ],
                    "sikumei" => [
                        0 => "市区名称は、必ず指定してください。"
                    ],
                    "sikumeiKana" => [
                        0 => "市区名称カナは、必ず指定してください。"
                    ],
                    "sikuCode" => [
                        0 => "JISコードは、必ず指定してください。"
                    ],
                    "town" => [
                        0 => "町域名称は、必ず指定してください。"
                    ],
                    "townKana" => [
                        0 => "町域名称カナは、必ず指定してください。"
                    ],
                ]
            ]);
    }

    /**
     * 更新処理
     *
     * @return void
     */
    public function test_update()
    {
        $response = $this->put("/zip_code/yusei/1", [
            'bulkUpdateDate' => '2022-05-15 00:00:00',
            'bulkUser' => '140',
            'addDate' => '2022-05-19 00:00:00',
            'User' => '140',
            'zipCode' => '1000011',
            'kenmei' => '東京都',
            'kenCode' => 13,
            'sikumei' => '千代田区',
            'sikuCode' => 13101,
            'kenmeiKana' => 'ﾄｳｷｮｳﾄ',
            'sikumeiKana' => 'ﾁﾖﾀﾞｸ',
            'town' => '日比谷公園テスト',
            'townKana' => 'ﾋﾋﾞﾔｺｳｴﾝﾃｽﾄ',
        ],

            [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

        $response->assertStatus(200);
    }

    /**
     * 更新処理（データなし）
     *
     * @return void
     */
    public function test_update_error_required()
    {
        $response = $this->put("/zip_code/yusei/2", [
        ],
            [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

        $response->dump()
            ->assertStatus(400)
            ->assertJson([
                'state' => 'V_0000000',
                'message' => 'バリデーションエラー',
                'result' => [
                    "zipCode" => [
                        0 => "郵便番号は、必ず指定してください。"
                    ],
                    "kenmei" => [
                        0 => "都道府県名は、必ず指定してください。"
                    ],
                    "kenmeiKana" => [
                        0 => "都道府県カナは、必ず指定してください。"
                    ],
                    "kenCode" => [
                        0 => "都道府県コードは、必ず指定してください。"
                    ],
                    "sikumei" => [
                        0 => "市区名称は、必ず指定してください。"
                    ],
                    "sikumeiKana" => [
                        0 => "市区名称カナは、必ず指定してください。"
                    ],
                    "sikuCode" => [
                        0 => "JISコードは、必ず指定してください。"
                    ],
                    "town" => [
                        0 => "町域名称は、必ず指定してください。"
                    ],
                    "townKana" => [
                        0 => "町域名称カナは、必ず指定してください。"
                    ],
                ]
            ]);
//        $response->assertStatus(402);
    }

    /**
     * 更新処理（データなし）
     *
     * @return void
     */
    public function test_update_illegal_request()
    {
        $response = $this->put("/zip_code/yusei/999", [
            'bulkUpdateDate' => '2022-05-15 00:00:00',
            'bulkUser' => '140',
            'addDate' => '2022-05-19 00:00:00',
            'User' => '140',
            'zipCode' => '1000012',
            'kenmei' => '東京都',
            'kenCode' => 13,
            'sikumei' => '千代田区',
            'sikuCode' => 13101,
            'kenmeiKana' => 'ﾄｳｷｮｳﾄ',
            'sikumeiKana' => 'ﾁﾖﾀﾞｸ',
            'town' => '日比谷公園テスト',
            'townKana' => 'ﾋﾋﾞﾔｺｳｴﾝﾃｽﾄ',
        ],
            [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

//        $response->dump()
//            ->assertStatus(500)
//            ->assertJson([
//                'state' => 'E_500_0000002',
//                'message' => '更新に失敗しました。',
//            ])
//        ;
        $response->assertStatus(500);
    }

    /**
     * 削除処理
     *
     * @return void
     */
    public function test_destroy()
    {
        $response = $this->put("/zip_code/yusei/99", [], [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

        $response->dump()
            ->assertStatus(400)
            ->assertJson([
                'state' => 'V_0000000',
                'message' => 'バリデーションエラー',
                'result' => [
                    "zipCode" => [
                        0 => "郵便番号は、必ず指定してください。"
                    ],
                ]
            ]);
//        $response->assertStatus(200);
    }

    /**
     * 削除処理
     *
     * @return void
     */
    public function test_destroy_illegal_request()
    {
        $response = $this->put("/zip_code/yusei/999", [], [
                'Authorization' => 'Bearer ' . $this->createAccessToken()
            ]
        );

        $response->dump()
            ->assertStatus(400)
            ->assertJson([
                'state' => 'V_0000000',
                'message' => 'バリデーションエラー',
            ]);
    }

}
