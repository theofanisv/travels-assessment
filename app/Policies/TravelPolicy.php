<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TravelPolicy
{
    use HandlesAuthorization;

    public function create(?User $user): bool|Response
    {
        return $user->hasAnyRole(Role::Admin)
            ?: $this->deny("User must be admin to create a new travel.");
    }

    public function update(User $user, Travel $travel): bool|Response
    {
        return $user->hasAnyRole([Role::Admin, Role::Editor])
            ?: $this->deny("User must be admin or editor to update a travel.");
    }

}
