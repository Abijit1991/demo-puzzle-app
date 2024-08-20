<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\DisplaySeederMessageTrait;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    use DisplaySeederMessageTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = config('constants.ROLES');

        collect($roles)->each(function ($slug, $name) {
            Role::updateOrCreate(
                ['slug' => trim($slug)],
                ['name' => trim(ucfirst(strtolower($name)))]
            );
        });

        $this->displayMessage(config('constants.SEEDER_SUCCESS_MSG.ROLE_SEEDER'));
    }
}
