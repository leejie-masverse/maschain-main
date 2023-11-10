<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Src\People\Repositories\SystemUserRepository;
use Src\People\SystemUser;
use Src\People\User;

class ManageAuthedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Resolve user repository
     *
     * @param User|null $user
     * @return mixed
     *
     */
    protected function resolveUserRepository(User $user = null)
    {
        $user = $user ?: Auth::user();

        return new SystemUserRepository;
    }

    /**
     * Resolve user model
     *
     * @param User|null $user
     * @return mixed
     */
    protected function resolveUserModel(User $user = null)
    {
        $user = $user ?: Auth::user();

        return new SystemUser;
    }
}
