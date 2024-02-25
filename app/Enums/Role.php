<?php

namespace App\Enums;

use App\Models\Role as RoleModel;

enum Role: string
{
    case Admin  = 'admin';
    case Editor = 'editor';

    public function getModel(): RoleModel
    {
        return RoleModel::where('name', $this->value)->firstOrFail();
    }
}
