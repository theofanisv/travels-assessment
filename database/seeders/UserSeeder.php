<?php

namespace Database\Seeders;

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        User::factory(5)->for(RoleEnum::Editor->getModel())->create();
        User::factory(2)->for(RoleEnum::Admin->getModel())->create();

        User::factory()
            ->for(RoleEnum::Admin->getModel())
            ->create([
                'name'     => 'Test Admin',
                'email'    => 'admin@example.com',
                'password' => 'password',
            ])->createToken('admin-demo');

        User::factory()
            ->for(RoleEnum::Editor->getModel())
            ->create([
                'name'     => 'Test Editor',
                'email'    => 'editor@example.com',
                'password' => 'password',
            ]);
    }
}
