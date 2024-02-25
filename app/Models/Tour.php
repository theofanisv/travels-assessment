<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'startingDate',
        'endingDate',
        'price',
        'decimalPrice',
    ];

    protected $casts = [
        'startingDate' => 'date',
        'endingDate'   => 'date',
    ];

    protected function decimalPrice(): Attribute
    {
        return Attribute::make(
            fn(): float => (float)number_format($this->price / 100, 2, '.', ''),
            fn(float $value) => ['price' => (int)($value * 100)],
        );
    }

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class, 'travelId');
    }
}
