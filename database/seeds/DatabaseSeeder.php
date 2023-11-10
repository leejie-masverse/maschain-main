<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DatasetSeeder::class);
        $this->call(BouncerSeeder::class);
        $this->call(SystemUserSeeder::class);
    }
}
