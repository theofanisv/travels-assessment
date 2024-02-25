<?php

namespace App\Http\Requests\Tour;

use App\Http\Resources\TourCollection;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property Travel $travel via route parameter
 */
class IndexToursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'priceFrom' => ['nullable', 'numeric', 'decimal:0,2'],
            'priceTo'   => ['nullable', 'numeric', 'decimal:0,2'],
            'dateFrom'  => ['nullable', 'date:Y-m-d'],
            'dateTo'    => ['nullable', 'date:Y-m-d'],
            'sorting'   => ['nullable', 'array:price'],
            'sorting.*' => ['required', 'string', 'in:asc,desc'],
            'perPage'   => ['nullable', 'integer', 'min:1', 'max:200'],
        ];
    }

    private function buildQuery(): LengthAwarePaginator
    {
        return Tour::query()
            // Check when url is under travel part to allow indexing from other urls that are not under a specific travel.
            ->when($this->travel,
                fn(Builder $q, Travel $travel) => $q->where('travelId', $travel->id),
            // Alternatively should we filter by only public travels?
            // fn(Builder $q) => $q->whereHas('travel', fn(Builder $q) => $q->where('isPublic', true)),
            // In my experience the following query is way faster than `whereHas`.
            // fn(Builder $q) => $q->whereIn('travelId', Travel::public()->select('id')),
            )
            ->when($this->input('priceFrom'), fn(Builder $q, $p) => $q->where('price', '>=', (int)($p * 100)))
            ->when($this->input('priceTo'), fn(Builder $q, $p) => $q->where('price', '<=', (int)($p * 100)))
            ->when($this->input('sorting'), function (Builder $q, $sorting) {
                foreach ($sorting as $field => $direction)
                    $q->orderBy($field, $direction);
            })
            ->orderBy('startingDate')
            ->paginate($this->integer('perPage', 20)); // Safeguard against external scanning (massive dump).
    }

    public function getResponse(): TourCollection
    {
        return (new TourCollection($this->buildQuery()))
            // When querying without filtering for a specific travel then append travel to results.
            // When querying for a particular travel do not append the travel to results.
            ->appendTravel(empty($this->travel));
    }
}
