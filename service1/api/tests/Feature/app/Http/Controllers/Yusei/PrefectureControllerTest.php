<?php

namespace Tests\Feature\app\Http\Controllers\Yusei;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrefectureControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get("/prefecture",
            [
                'Authorization' => 'Bearer '.$this->createAccessToken()
            ]
        );

        $response->assertStatus(200);
    }
}
