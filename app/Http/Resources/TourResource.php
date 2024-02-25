<?php

namespace App\Http\Resources;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Tour $resource
 */
class TourResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return $this->resource->only([
                'id',
                'name',
                'startingDate',
                'endingDate',
                'decimalPrice',
            ]) + [
                'travel' => $this->whenLoaded('travel'),
            ];
    }

}
