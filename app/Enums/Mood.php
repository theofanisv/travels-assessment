<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum Mood: string
{
    case Nature  = 'nature';
    case Relax   = 'relax';
    case History = 'history';
    case Culture = 'culture';
    case Party   = 'party';

    public static function keys(): Collection
    {
        return collect(static::cases())->pluck('value');
    }
}
