<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\AuthedController;
use App\Http\Requests\v1\Auth\User\UpdatePasswordRequest;
use App\Http\Requests\v1\Auth\User\UpdateRequest;
use App\Http\Transformers\v1\UserTransformer;
use Illuminate\Support\Facades\Auth;

class UserController extends AuthedController
{
    /**
     * Show user
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show()
    {
        return $this->response->item(Auth::user(), new UserTransformer);
    }

    /**
     * Update user
     *
     * @param \App\Http\Requests\v1\Auth\User\UpdateRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $user = Auth::user();

        $input['user']['email'] = $request->input('email');
        $input['user_profile']['full_name'] = $request->input('full_name');
        $input['user_phone']['phone'] = $request->input('phone');

        $this->resolveUserRepository()->update($user, $input);

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Update password
     *
     * @param \App\Http\Requests\v1\Auth\User\UpdatePasswordRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $this->resolveUserRepository()->updatePassword($user, $request->input('password'));

        return $this->response->item($user, new UserTransformer);
    }
}
