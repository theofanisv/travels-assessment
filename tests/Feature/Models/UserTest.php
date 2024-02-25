<?php

use App\Enums\Role as RoleEnum;

beforeEach(function () {
    $this->seed();
});

test('has any role', function () {
    $user = getUserByRole();

    expect($user->hasAnyRole(null))->toBeTrue()
        ->and($user->hasAnyRole('invalid-role'))->toBeFalse()
        ->and($user->hasAnyRole(RoleEnum::Editor->getModel()))->toBeFalse()
        ->and($user->hasAnyRole(RoleEnum::Admin))->toBeFalse()
        ->and($user->hasAnyRole([RoleEnum::Editor, 'admin']))->toBeFalse()
        ->and($user->hasAnyRole([RoleEnum::Editor, 'admin', null]))->toBeTrue();

    $user = getUserByRole(RoleEnum::Admin);

    expect($user->hasAnyRole(null))->toBeFalse()
        ->and($user->hasAnyRole('invalid-role'))->toBeFalse()
        ->and($user->hasAnyRole(RoleEnum::Editor->getModel()))->toBeFalse()
        ->and($user->hasAnyRole(RoleEnum::Admin))->toBeTrue()
        ->and($user->hasAnyRole(RoleEnum::Admin->getModel()))->toBeTrue()
        ->and($user->hasAnyRole([RoleEnum::Editor, null, 'admin']))->toBeTrue()
        ->and($user->hasAnyRole([RoleEnum::Editor, 'editor', null]))->toBeFalse();
});