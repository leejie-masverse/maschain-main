<?php

namespace App\Http\Controllers\Manage\People;

use App\Http\Controllers\ManageAuthedController;
use App\Http\Requests\Manage\People\Users\QueryRequest;
use App\Http\Requests\Manage\People\Users\StoreRequest;
use App\Http\Requests\Manage\People\Users\UpdatePasswordRequest;
use App\Http\Requests\Manage\People\Users\UpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Auth\Ability;
use Src\Auth\Role;
use Src\People\Facades\SystemUserRepository;
use Src\People\User;
use function ceil;
use function now;

class UsersController extends ManageAuthedController
{
    /**
     * Get the permissions that apply to the controller.
     *
     * @return array
     */
    protected function permissions()
    {
        return [
          Ability::MANAGE_ROOTS,
          Ability::MANAGE_USERS,
        ];
    }

    /**
     * List action
     *
     * @param \App\Http\Requests\Manage\People\Users\QueryRequest $queried
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(QueryRequest $queried)
    {
        $users = $queried->query()->whereIs(Role::SYSTEM_USER)->latest()->paginate(20);

        return view('manage.people.users.list', compact('users'))->with([
            'filters' => $queried->filters(),
        ]);
    }

    /**
     * Edit action
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::whereIs(Role::SYSTEM_USER)->findOrFail($id);

        return view('manage.people.users.edit', compact('user'));
    }

    /**
     * Update action
     *
     * @param \App\Http\Requests\Manage\People\Users\UpdateRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = User::whereIs(Role::SYSTEM_USER)->findOrFail($id);

        $input['user']['email'] = $request->input('email');
        $input['user_profile']['full_name'] = $request->input('full_name');
        $input['user_portrait']['file'] = $request->file('portrait');

        $user = SystemUserRepository::update($user, $input);

        flash()->success("User <strong>{$user->profile->full_name}</strong> is updated.");

        return back();
    }

    /**
     * Edit password action
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPassword($id)
    {
        $user = User::whereIs(Role::SYSTEM_USER)->findOrFail($id);

        return view('manage.people.users.edit-password', compact('user'));
    }

    /**
     * Update password action
     *
     * @param \App\Http\Requests\Manage\People\Users\UpdatePasswordRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        $user = User::whereIs(Role::SYSTEM_USER)->findOrFail($id);

        $user = SystemUserRepository::updatePassword($user, $request->input('password'));

        flash()->success("User <strong>{$user->profile->full_name}</strong> password is updated.");

        return back();
    }

    /**
     * Ban action
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function ban($id)
    {
        $user = User::active()->whereIs(Role::SYSTEM_USER)->findOrFail($id);

        SystemUserRepository::ban($user);

        flash()->success("User <strong>{$user->profile->full_name}</strong> is banned.");

        return back();
    }

    /**
     * Unban action
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function unban($id)
    {
        $user = User::banned()->whereIs(Role::SYSTEM_USER)->findOrFail($id);

        SystemUserRepository::unban($user);

        flash()->success("User <strong>{$user->profile->full_name}</strong> is unbanned.");

        return back();
    }

    /**
     * Verify user
     *
     * @group
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($id)
    {
        $user = User::verifying()->whereIs(Role::SYSTEM_USER)->findOrFail($id);

        SystemUserRepository::verify($user);

        flash()->success("User <strong>{$user->profile->full_name}</strong> is active.");

        return back();
    }

    /**
     * Release action
     * For upgrading partner level, or user to partner usage.
     * This will make sure the referral tree won't collapse, and new customer can reuse back same phone numer
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function release($id)
    {
        $user = User::whereIs(Role::SYSTEM_USER)->findOrFail($id);

        SystemUserRepository::release($user);

        flash()->success("User <strong>{$user->profile->full_name}</strong> is released.");

        return back();
    }

    /**
     * Destroy action
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $user = User::whereIs(Role::SYSTEM_USER)->findOrFail($id);

        SystemUserRepository::delete($user);

        flash()->success("User <strong>{$user->profile->full_name}</strong> is deleted.");

        return back();
    }

}
