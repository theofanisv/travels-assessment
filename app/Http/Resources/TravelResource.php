<?php

namespace App\Http\Resources;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\This;

/**
 * @property Travel $resource
 */
class TravelResource extends JsonResource
{
    public bool $withThumbnail = false;
    public bool $withPhotos = false;

    public function toArray(Request $request): array
    {
        return $this->resource->only([
                'id',
                'name',
                'slug',
                'description',
                'numberOfDays',
                'numberOfNights',
                'moods',
            ]) + [
                'thumbnail' => $this->when($this->withThumbnail, fn() => MediaResource::make($this->resource->getFirstMedia('thumbnail'))),
                'photos'    => $this->when($this->withPhotos, fn() => MediaResource::collection($this->resource->getMedia('photos')->all())),
            ];
    }

    public function withThumbnail(bool $with = true): static
    {
        $this->withThumbnail = $with;
        return $this;
    }

    public function withPhotos(bool $with = true): static
    {
        $this->withPhotos = $with;
        return $this;
    }


}
