<?php

use Illuminate\Database\Seeder;
use Src\Auth\Ability;
use Src\Auth\Role;
use Silber\Bouncer\BouncerFacade as Bouncer;

class BouncerSeeder extends Seeder
{
    /**
     * Roles
     *
     * @var array
     */
    protected $roles = [
        ['name' => Role::SYSTEM_ROOT],
        ['name' => Role::SYSTEM_ADMINISTRATOR],
        ['name' => Role::SYSTEM_USER],
    ];

    /**
     * Permissions
     *
     * @var array
     */
    protected $permissions = [
        Ability::MANAGE_ROOTS => [ Role::SYSTEM_ROOT ],
        Ability::MANAGE_ADMINISTRATORS => [ Role::SYSTEM_ROOT, Role::SYSTEM_ADMINISTRATOR ],
        Ability::MANAGE_USERS => [ Role::SYSTEM_ROOT, Role::SYSTEM_ADMINISTRATOR ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedRoles();
        $this->seedPermissions();
    }

    /**
     * Seed roles
     */
    protected function seedRoles()
    {
        collect($this->roles)->each(function ($role) {
           Bouncer::role()->create($role);
        });
    }

    /**
     * Seed permissions
     */
    protected function seedPermissions()
    {
        collect($this->permissions)->each(function ($roles, $ability) {
            $this->seedPermission($ability, $roles);
        });
    }

    /**
     * Seed a permission
     *
     * @param $ability
     * @param $roles
     */
    protected function seedPermission($ability, $roles)
    {
        collect($roles)->each(function ($role) use ($ability) {
            Bouncer::allow($role)->to($ability);
        });
    }
}
