<?php

namespace App\Http\Controllers\Manage\People;

use App\Http\Controllers\ManageAuthedController;
use App\Http\Requests\Manage\People\Admins\QueryRequest;
use App\Http\Requests\Manage\People\Admins\StoreRequest;
use App\Http\Requests\Manage\People\Admins\UpdatePasswordRequest;
use App\Http\Requests\Manage\People\Admins\UpdateRequest;
use Src\Auth\Ability;
use Src\Auth\Role;
use Src\People\Facades\SystemUserRepository;
use Src\People\Facades\UserRepository;
use Src\People\User;
use function compact;

class AdminsController extends ManageAuthedController
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
        ];
    }

    /**
     * List action
     *
     * @param \App\Http\Requests\Manage\People\Admins\QueryRequest $queried
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(QueryRequest $queried)
    {
        $users = $queried->query()->whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->paginate(20);

        return view('manage.people.admins.list', compact('users'))->with([
            'filters' => $queried->filters(),
        ]);
    }

    /**
     * Create action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $abilities = Ability::getAllAdminAbilities();

        return view('manage.people.admins.create', compact('abilities'));
    }

    /**
     * Store action
     *
     * @param \App\Http\Requests\Manage\People\Admins\StoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store(StoreRequest $request)
    {
        $input['user']['email'] = $request->input('email');
        $input['user']['password'] = $request->input('password');
        $input['user']['created_by'] = auth()->user() ? auth()->user()->id : null;
        $input['user_profile']['full_name'] = $request->input('full_name');
        $input['user_abilities'] = $request->input('abilities');

        $user = SystemUserRepository::createAdministrator($input);

        flash()->success("Admin <strong>{$user->profile->full_name}</strong> is created.");

        return redirect(route('manage.people.admins.list'));
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
        $user = User::whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);
        $abilities = Ability::getAllAdminAbilities();

        return view('manage.people.admins.edit', compact('user','abilities'));
    }

    /**
     * Update action
     *
     * @param \App\Http\Requests\Manage\People\Admins\UpdateRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = User::whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);

        $input['user']['email'] = $request->input('email');
        $input['user']['updated_by'] = auth()->user() ? auth()->id() : null;
        $input['user_profile']['full_name'] = $request->input('full_name');
        $input['user_abilities'] = $request->input('abilities');

        $newRole = $request->input('role');
        if ($user->role->name == $newRole) {
            $newRole = null;
        }

        $user = SystemUserRepository::update($user, $input, $newRole);

        flash()->success("Admin <strong>{$user->profile->full_name}</strong> is updated.");

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
        $user = User::whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);

        return view('manage.people.admins.edit-password', compact('user'));
    }

    /**
     * Update password action
     *
     * @param \App\Http\Requests\Manage\People\Admins\UpdatePasswordRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        $user = User::whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);

        $user = SystemUserRepository::updatePassword($user, $request->input('password'));

        flash()->success("Admin <strong>{$user->profile->full_name}</strong> password is updated.");

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
        $user = User::active()->whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);

        SystemUserRepository::ban($user);

        flash()->success("Admin <strong>{$user->profile->full_name}</strong> is banned.");

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
        $user = User::banned()->whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);

        SystemUserRepository::unban($user);

        flash()->success("Admin <strong>{$user->profile->full_name}</strong> is unbanned.");

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
        $user = User::whereIs([Role::SYSTEM_ADMINISTRATOR, Role::SYSTEM_ROOT])->findOrFail($id);

        SystemUserRepository::delete($user);

        flash()->success("Admin <strong>{$user->profile->full_name}</strong> is deleted.");

        return back();
    }
}
