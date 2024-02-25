<?php

use App\Enums\Role as RoleEnum;

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

test('TourPolicy create', function (?RoleEnum $role, bool $result) {
    expect(getUserByRole($role)->can('create', \App\Models\Tour::class))
        ->toEqual($result);
})->with([
    'Admin'    => [RoleEnum::Admin, true],
    'Editor'   => [RoleEnum::Editor, false],
    'Customer' => [null, false],
]);
