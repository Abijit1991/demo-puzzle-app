<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\DisplaySeederMessageTrait;
use App\Models\Role;

/**
 * RoleSeeder
 *
 * Seed the roles table with the provided data.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class RoleSeeder extends Seeder
{
    use DisplaySeederMessageTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve the roles from the configuration file.
        $roles = config('constants.ROLES');

        // Iterate over each role and either update or create it in the database.
        collect($roles)->each(function ($slug, $name) {
            Role::updateOrCreate(
                ['slug' => trim($slug)],
                ['name' => trim(ucfirst(strtolower($name)))]
            );
        });

        // Display a message after seeding is complete.
        $this->displayMessage(config('constants.SEEDER_SUCCESS_MSG.ROLE_SEEDER'));
    }
}
