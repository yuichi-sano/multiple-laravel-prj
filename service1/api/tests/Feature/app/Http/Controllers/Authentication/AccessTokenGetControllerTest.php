<?php

namespace Tests\Feature\app\Http\Controllers\Authentication;

use App\Exceptions\ErrorCodeConst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessTokenGetControllerTest extends TestCase
{
    /**
     * 取得テスト
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->post('/refresh',[
            'refreshToken' => 'test'
        ]);

        $response->assertStatus(200);
    }

    /**
     * 取得テスト（必須エラー）
     *
     * @return void
     */
    public function test_index_error_required()
    {
        $response = $this->post('/refresh',[
            'refreshToken' => ''
        ]);

        $response->dump()
            ->assertStatus(400)
            ->assertJson([
                'state' => ErrorCodeConst::VALIDATION,
                'message' => 'バリデーションエラー',
                'result' => [
                    "refreshToken" => [
                        0 => "リフレッシュトークンは、必ず指定してください。"
                    ],
                ]
            ])
        ;
    }
}
