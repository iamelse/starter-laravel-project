<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserDatabaseSeeder::class,
        ]);
    }
}
