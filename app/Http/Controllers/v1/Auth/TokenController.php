<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\Token\LoginRequest;
use App\Http\Transformers\v1\TokenTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Src\People\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
    }

    protected function guard()
    {
        return Auth::guard('api');
    }

    /**
     * Login action
     *
     * @param LoginRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = User::extractPhoneInfo($request->input('phone_prefix'), $request->input('phone_number'));
        $credentials['password'] = $request->input('password');

        $token = auth()->attempt($credentials);
        if ( ! $token) {
            return $this->response->errorUnauthorized();
        }

        $transformed = (new TokenTransformer)->transform($token);

        return $this->response->created()->setContent(['data' => $transformed]);
    }

    /**
     * Refresh token action
     *
     * @return \Dingo\Api\Http\Response
     */
    public function refresh()
    {
        $newToken = auth()->refresh();

        $transformed = (new TokenTransformer)->transform($newToken);

        return $this->response->created()->setContent(['data' => $transformed]);
    }

    /**
     * Expire token action
     *
     * @return \Dingo\Api\Http\Response
     */
    public function expire()
    {
        auth()->logout();

        return $this->response->noContent();
    }
}
