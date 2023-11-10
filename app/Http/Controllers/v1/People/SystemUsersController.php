<?php

namespace App\Http\Controllers\v1\People;

use App\Http\Controllers\AuthedController;
use App\Http\Requests\v1\People\SystemUsers\QueryRequest;
use App\Http\Requests\v1\People\SystemUsers\StoreRequest;
use App\Http\Requests\v1\People\SystemUsers\UpdatePasswordRequest;
use App\Http\Requests\v1\People\SystemUsers\UpdateRequest;
use App\Http\Transformers\v1\SystemUserTransformer;
use App\Http\Transformers\v1\UserTransformer;
use Src\Auth\Role;
use Src\People\Facades\SystemUserRepository;
use Src\People\SystemUser;
use Src\People\User;

class SystemUsersController extends AuthedController
{
    /**
     * Store action
     *
     * @param \App\Http\Requests\v1\People\SystemUsers\StoreRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data['user']['password'] = $request->input('password');

        $phoneNumber = User::extractPhoneInfo($request->input('phone_prefix'), $request->input('phone_number'));

        $input['user']['phone_code'] = $phoneNumber['phone_code'];
        $input['user']['phone_number'] = $phoneNumber['phone_number'];
        $input['user']['formatted_phone_number'] = $phoneNumber['formatted_phone_number'];

        $user = SystemUserRepository::createUser($data);

        return $this->response->item($user, new SystemUserTransformer)->setStatusCode(201);
    }

    /**
     * Show action
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $user = SystemUser::ownedBy()->findOrFail($id);

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Update action
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = SystemUser::ownedBy()->findOrFail($id);

        $data['user']['email']             = $request->input('email');
        $data['user_profile']['full_name'] = $request->input('full_name');
        $data['user_phone']['phone']       = $request->input('phone');

//        $newRole = $request->input('role');
//        if ($user->role->name == $newRole) {
//            $newRole = null;
//        }

//        $user = SystemUserRepository::update($user, $data, $newRole);
        $user = SystemUserRepository::update($user, $data);

        return $this->response->item($user, new SystemUserTransformer);
    }

    /**
     * Update password action
     *
     * @param \App\Http\Requests\v1\People\SystemUsers\UpdatePasswordRequest $request
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        $user = SystemUser::ownedBy()->findOrFail($id);

        SystemUserRepository::updatePassword($user, $request->input('password'));

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Ban action
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function ban($id)
    {
        $user = SystemUser::active()->ownedBy()->findOrFail($id);

        SystemUserRepository::ban($user);

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Unban action
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function unban($id)
    {
        $user = SystemUser::banned()->ownedBy()->findOrFail($id);

        SystemUserRepository::unban($user);

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Destroy action
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $user = SystemUser::ownedBy()->findOrFail($id);

        $isDeleted = SystemUserRepository::delete($user);
        if ( ! $isDeleted) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($user, new UserTransformer);
    }
}
