<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Authentication\HealthCheckResource;

class HealthCheckController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * APIサーバーヘルスチェック
     * @param mixed
     */
    public function index(): HealthCheckResource
    {
        return HealthCheckResource::buildResult();
    }
}
