<?php

use App\Enums\Role as RoleEnum;

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

test('TravelPolicy create', function (?RoleEnum $role, bool $result) {
    expect(getUserByRole($role)->can('create', \App\Models\Travel::class))
        ->toEqual($result);
})->with([
    'Admin'    => [RoleEnum::Admin, true],
    'Editor'   => [RoleEnum::Editor, false],
    'Customer' => [null, false],
]);

test('TravelPolicy update', function (?RoleEnum $role, bool $result) {
    expect(getUserByRole($role)->can('update', [new \App\Models\Travel()]))
        ->toEqual($result);
})->with([
    'Admin'    => [RoleEnum::Admin, true],
    'Editor'   => [RoleEnum::Editor, true],
    'Customer' => [null, false],
]);