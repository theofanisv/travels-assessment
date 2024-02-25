<?php

namespace Database\Seeders;

use App\Enums\Role as RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        collect(RoleEnum::cases())
            ->map(fn(RoleEnum $role) => Role::firstOrCreate(['name' => $role->value]));
    }
}
