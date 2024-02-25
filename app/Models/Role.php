<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Enum;

class Role extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => RoleEnum::class,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'roleId');
    }
}
