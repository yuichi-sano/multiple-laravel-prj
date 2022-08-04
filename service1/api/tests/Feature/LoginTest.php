<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use LaravelDoctrine\ORM\Facades\EntityManager;

use function PHPUnit\Framework\assertTrue;

class LoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_cleandb()
    {
        Artisan::call('flyway:testing');
        assertTrue(true);
    }

    /**
     * @return void
     */
    public function test_login() {
        $response = $this->post('/login', [
            'UserId' => 'sample',
            'password' => 'sample'
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * assert書き方
     * https://readouble.com/laravel/8.x/ja/http-tests.html
     *
     * @return void
     */
    public function test_error_password_required()
    {
        $response = $this->post('/login', [
            'UserId' => 'sample',
        ]);

        $response->dump()
            ->assertStatus(400)
            ->assertJson([
                'result' => [
                    'password' => [
                        0 => 'パスワードは、必ず指定してください。'
                    ]
                ]
            ])
        ;
    }

    /**
     * GETメソッドは405エラー
     */
    public function test_error_getmethod(){
        $response = $this->get('/login');
        $response->assertStatus(405);
    }

}
