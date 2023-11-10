<?php

namespace Src\People\Repositories;

use Src\Auth\Role;
use Src\People\SystemUser;
use Src\People\User;

class SystemUserRepository extends UserRepository
{
    /**
     * Get system user model
     *
     * @return \Src\People\SystemUser
     */
    protected static function getUserModel()
    {
        return new SystemUser;
    }

    /**
     * Create system root user
     *
     * @param array $input
     *
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function createRoot(array $input)
    {
        $input['user']['status'] = User::STATUS_ACTIVE;

        return $this->createWithRole($input, Role::SYSTEM_ROOT);
    }

    /**
     * Create system administrator user
     *
     * @param array $input
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function createAdministrator(array $input)
    {
        $input['user']['status'] = User::STATUS_ACTIVE;

        return $this->createWithRole($input, Role::SYSTEM_ADMINISTRATOR);
    }

    /**
     * Create system standard user
     *
     * @param array $input
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function createUser(array $input)
    {
        return $this->createWithRole($input, Role::SYSTEM_USER);
    }

    /**
     * Update system user
     *
     * @param \Src\People\User $user
     * @param array $input
     * @param \Src\Auth\Role $newRole
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function update(User $user, array $input, $newRole = null)
    {
//        if (!($user instanceof SystemUser)) {
//            throw new \InvalidArgumentException('$user is not an instance of ' . SystemUser::class);
//        }

        return parent::update($user, $input, $newRole);
    }
}
