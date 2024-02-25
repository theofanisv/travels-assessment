<?php

use App\Enums\Role as RoleEnum;
use Database\Seeders\RoleSeeder;

test('all roles exist', function (RoleEnum $role) {
    $this->seed(RoleSeeder::class);
    expect($role->getModel()->name)->toBe($role);
})->with(RoleEnum::cases());