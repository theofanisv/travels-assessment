<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function hasAnyRole(Role|RoleEnum|string|null|iterable $roles): bool
    {
        $roles = is_iterable($roles) ? $roles : (Arr::wrap($roles) ?: [null]);

        foreach ($roles as $role) {
            $name = match (true) {
                $role instanceof RoleEnum => $role->value,
                $role instanceof Role => $role->name->value,
                default => $role
            };

            if ($this->role?->name?->value == $name)
                return true;
        }

        return false;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roleId');
    }
}
