<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TourPolicy
{
    use HandlesAuthorization;

    public function viewAnyByTravel(?User $user, ?Travel $travel): bool|Response
    {
        return $user?->hasAnyRole([Role::Admin, Role::Editor]) // If user is privileged then show results even for non-public travels.
            ?: $travel?->isPublic // For customer show tours only for public travels.
                ?: $this->deny("User does not have permissions to see tours for private travel.");
    }

    public function create(User $user, ?Travel $travel = null): bool|Response
    {
        return $user->hasAnyRole(Role::Admin)
            ?: $this->deny("User must be admin to create a new tour.");
    }

}
