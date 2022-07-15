<?php

namespace Tests\Feature\app\Http\Controllers\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckControllerTest extends TestCase
{
    /**
     * 取得テスト
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/health_check',
            [
                'Authorization' => 'Bearer '.$this->createAccessToken()
            ]);

        $response->assertStatus(200);
    }

}
