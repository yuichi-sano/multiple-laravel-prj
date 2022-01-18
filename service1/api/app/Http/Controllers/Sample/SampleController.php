<?php

namespace App\Http\Controllers\Sample;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Sample\SampleRequest;
use App\Http\Resources\Sample\SampleResource;
use packages\domain\model\User\UserId;
use packages\service\merchant\MerchantGetInterface;
use packages\service\UserGetInterface;

class SampleController extends BaseController
{
    private MerchantGetInterface $merchantGet;
    private UserGetInterface $userGet;
    public function __construct(MerchantGetInterface $merchantGet, UserGetInterface $userGet)
    {
        $this->merchantGet=$merchantGet;
        $this->userGet=$userGet;
    }

    /**
     * test
     * @param mixed
     */
    public function index(SampleRequest $request)
    {
        //

        $userId = new UserId(1);
        //$response = $this->userGet->execute($userId);
        //$response = $this->merchantGet->execute(1);
        if($request->getHoge() == 1){
            $response = $this->merchantGet->execute(1);
        }
        $response = $this->merchantGet->getList();
        //var_dump($response);


        return SampleResource::buildResult($response);
    }

}
