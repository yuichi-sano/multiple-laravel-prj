<?php

namespace App\Http\Controllers;

use App\Http\Requests\SampleRequest;
use App\Http\Resources\SampleResource;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use packages\Service\UserGetInterface;
use Illuminate\Support\Facades\Auth;
class AuthController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(SampleRequest $request, UserGetInterface $userGet)
    {

        //Auth::guard('api')->getProvider()->setHasher(app('md5hash'));
        if (! $token= Auth::attempt($request->validated())) {

            return response()->json([
                'Invalid credential'
            ], 400);
        }
        $ref = Auth::refresh();

        return response()->json([$token,$ref]);
    }

}
