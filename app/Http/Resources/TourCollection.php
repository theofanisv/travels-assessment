<?php

namespace App\Http\Resources;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @property Collection<Tour> $collection
 */
class TourCollection extends ResourceCollection
{
    private bool $appendTravel = false;

    public function toArray(Request $request): array
    {
        if ($this->appendTravel) {
            $this->collection->loadMissing('travel'); // Eager load travel relation
        }

        return $this->collection->all();
    }

    public function with(Request $request): array
    {
        return [
            'travel' => $this->when($travel = $request->travel,
                fn() => TravelResource::make($travel)
            ),
        ];
    }

    public function appendTravel(bool $appendTravel = true): static
    {
        $this->appendTravel = $appendTravel;

        return $this;
    }
}
