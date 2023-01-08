<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create([
            'name' => 'Rizky Putra Ednar',
            'email' => 'rizkyputraednar@gmail.com',
        ]);

        $user->assignRole(Roles::SUPERADMIN->value);
    }
}
