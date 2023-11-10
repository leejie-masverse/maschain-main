<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Auth\Role;
use Src\People\Facades\SystemUserRepository;
use Src\People\User;

class SystemUserSeeder extends Seeder
{
    /**
     * Users
     *
     * @var array
     */
    protected $users = [
        Role::SYSTEM_ROOT => [
            ['user.email' => 'root@system.com'],
        ],
        Role::SYSTEM_ADMINISTRATOR => [
            ['user.email' => 'admin@system.com'],
            ['user.email' => 'admin2@system.com'],
        ],
        Role::SYSTEM_USER => [
            [
                'user.email' => 'user@system.com',
                'user.phone_code' => '+60',
                'user.phone_number' => '123',
                'user.formatted_phone_number' => '60123',
            ],
            [
                'user.email' => 'user2@system.com',
                'user.phone_code' => '+60',
                'user.phone_number' => '124',
                'user.formatted_phone_number' => '60124',
            ],
            [
                'user.email' => 'user3@system.com',
                'user.phone_code' => '+60',
                'user.phone_number' => '125',
                'user.formatted_phone_number' => '60125',
            ],
            [
                'user.email' => 'user4@system.com',
                'user.phone_code' => '+60',
                'user.phone_number' => '126',
                'user.formatted_phone_number' => '60126',
            ],
            [
                'user.email' => 'user5@system.com',
                'user.phone_code' => '+60',
                'user.phone_number' => '127',
                'user.formatted_phone_number' => '60127',
            ],
        ],
    ];

    /**
     * Faker generator
     *
     * @var Faker
     */
    protected $faker;

    /**
     * UserSeeder constructor.
     *
     * @param Faker $faker
     */
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedSystemRoots();
        $this->seedSystemAdministrators();
        $this->seedSystemUsers();
    }

    /**
     * seed system roots
     */
    protected function seedSystemRoots()
    {
        collect($this->users[Role::SYSTEM_ROOT])->each(function ($data) {
            $input = hash_expand($data);
            $input['user']['password'] = 'password';
            $input['user_profile']['full_name'] = $this->faker->name;

            SystemUserRepository::createRoot($input);
        });
    }

    /**
     * seed system administrators
     */
    protected function seedSystemAdministrators()
    {
        collect($this->users[Role::SYSTEM_ADMINISTRATOR])->each(function ($data) {
            $input = hash_expand($data);
            $input['user']['password'] = 'password';
            $input['user_profile']['full_name'] = $this->faker->name;

            SystemUserRepository::createAdministrator($input);
        });
    }

    /**
     * seed system users
     */
    protected function seedSystemUsers()
    {
        collect($this->users[Role::SYSTEM_USER])->each(function ($data) {
            $input = hash_expand($data);
            $input['user']['password'] = 'password';
            $input['user']['status'] = User::STATUS_ACTIVE;
            $input['user_profile']['full_name'] = $this->faker->name;

            SystemUserRepository::createUser($input);
        });
    }

}
