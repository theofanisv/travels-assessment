<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Media $resource
 */
class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource->only(
                'id',
                'uuid',
                'name',
                'file_name',
                'mime_type',
                'size',
                'original_url',
                'preview_url',
            ) + [
                'conversions' => $this->resource
                    ->getGeneratedConversions()
                    ->filter()
                    ->map(fn($has, string $conversion) => $this->resource->getUrl($conversion))
                    ->all(),
            ];
    }
}
