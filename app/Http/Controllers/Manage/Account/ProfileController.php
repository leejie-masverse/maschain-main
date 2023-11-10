<?php

namespace App\Http\Controllers\Manage\Account;

use App\Http\Controllers\ManageAuthedController;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Src\People\Facades\SystemUserRepository;

class ProfileController extends ManageAuthedController
{
    /**
     * Edit action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();

        return view('manage.account.profile.edit', compact('user'));
    }

    /**
     * Update action
     *
     * @param \App\Http\Requests\Profile\UpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function update(UpdateRequest $request)
    {
        $user = Auth::user();

        $input['user']['email']             = $request->input('email');
        $input['user_profile']['full_name'] = $request->input('full_name');
        $input['user_portrait']['file']     = $request->file('portrait');

        SystemUserRepository::update($user, $input);

        flash()->success('Your account has been successfully updated');

        return redirect(route('manage.account.profile.edit'));
    }

    /**
     * Edit password action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPassword()
    {
        return view('manage.account.profile.edit-password');
    }

    /**
     * Update password action
     *
     * @param \App\Http\Requests\Profile\UpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        SystemUserRepository::updatePassword($user, $request->input('password'));

        flash()->success('Your password has been successfully changed');

        return redirect(route('manage.account.profile.update-password'));
    }
}
