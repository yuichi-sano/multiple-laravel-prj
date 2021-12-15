<?php

namespace App\Http\Controllers\Sample;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Sample\SampleRequest;
use App\Http\Resources\Sample\SampleResource;
use packages\domain\model\User\UserId;
use packages\service\UserGetInterface;

class SampleController extends BaseController
{

    public function __construct()
    {
    }

    /**
     * test
     * @param mixed
     */
    public function index(SampleRequest $request, UserGetInterface $userGet)
    {
        //
        $userId = new UserId($request->getHoge());
        $response = $userGet->execute($userId);
        return SampleResource::buildResult($response);
    }

}
