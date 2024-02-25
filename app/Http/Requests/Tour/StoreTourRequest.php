<?php

namespace App\Http\Requests\Tour;

use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Auth\Access\Response as AuthResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property Travel $travel via route parameter
 */
class StoreTourRequest extends FormRequest
{
    public Tour $tour;

    public function authorize(): AuthResponse
    {
        return Gate::authorize('create', [Tour::class, $this->travel]);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string'],
            'startingDate' => ['required', 'date_format:Y-m-d'],
            'decimalPrice' => ['required', 'numeric', 'decimal:2'],
        ];
    }

    public function handle(): static
    {
        $this->tour = new Tour($this->validated());
        $travel = $this->travel;
        $this->tour->endingDate = $this->tour->startingDate->clone()->addDays($travel->numberOfDays);
        $travel->tours()->save($this->tour);

        return $this;
    }

    public function getResponse(): TourResource
    {
        return TourResource::make($this->tour);
    }
}
