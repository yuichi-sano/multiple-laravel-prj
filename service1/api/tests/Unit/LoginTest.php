<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    // function名、比較用変数の値は仮

    /**
     * 認証チェック成功時レスポンステスト
     *
     * @return void
     */
    public function test_checkCertification() {
        //  結果コード (string)
        $state = '';
        //  メッセージ (string)
        $message = '';

        // TODO 取得処理呼び出し

        //  結果コード
        //  $this->assertSame($state, $response->state);
        //  メッセージ
        //  $this->assertSame($message, $response->message);
    }


    /**
     * APIサーバーヘルスチェック成功時レスポンステスト
     *
     * @return void
     */
    public function test_checkServerHealth() {
        //  結果コード (string)
        $state = '';
        //  メッセージ (string)
        $message = '';

        // TODO 取得処理呼び出し

        //  結果コード
        //  $this->assertSame($state, $response->state);
        //  メッセージ
        //  $this->assertSame($message, $response->message);
    }
}
