<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Travel extends Model implements HasMedia
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'travels';

    protected $attributes = [
        'moods' => '{}',
    ];
    protected $fillable = [
        'slug',
        'name',
        'description',
        'isPublic',
        'numberOfDays',
        'moods',
    ];

    protected $casts = [
        'isPublic' => 'boolean',
        'moods'    => 'array',
    ];

    public function scopePublic(Builder $query, bool $is_public = true): Builder
    {
        return $query->where('isPublic', $is_public);
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return empty($field)
            ? $query->orWhere(['slug' => $value, 'id' => $value])
            : parent::resolveRouteBindingQuery($query, $value, $field);
    }

    public function registerMediaCollections(Media $media = null): void
    {
        $imageMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
//        $this
//            ->addMediaConversion('mobile')
//            ->fit(Fit::Fill, 150, 150)
//            ->performOnCollections('thumbnail');
//
//        $this
//            ->addMediaConversion('desktop')
//            ->fit(Fit::Fill, 300, 300)
//            ->performOnCollections('thumbnail');

        $this->addMediaCollection('thumbnail')
            //->acceptsFile(fn(File $file) => Str::is('image/*', $file->mimeType))
            ->acceptsMimeTypes($imageMimeTypes)
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('mobile')
                    ->fit(Fit::Fill, 150, 150);

                $this
                    ->addMediaConversion('desktop')
                    ->fit(Fit::Fill, 300, 300);
            });

        $this->addMediaCollection('photos')
            ->acceptsMimeTypes($imageMimeTypes)
            ->registerMediaConversions(function (Media $media) {
                // xlarge 1620 x 1080
                // large 1280 x 850
                // medium 960 x 640
                // small 630 x 420
                $this->addMediaConversion('mobile')
                    ->width(630)
                    ->height(420);

                $this->addMediaConversion('desktop')
                    ->width(1620)
                    ->height(1080);
            });
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'travelId');
    }

}
