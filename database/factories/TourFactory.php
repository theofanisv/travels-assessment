<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    public function definition(): array
    {
        return [
            'travelId'     => null, // Provided through state
            'name'         => str($this->faker->words(3, true))->title(),
            'startingDate' => now()->addDays(random_int(1, 100)),
            'endingDate'   => null, // Provided through state
            'price'        => $this->faker->numberBetween(100, 7000) * 100,
        ];
    }

    public function forTravel(Travel $travel): TourFactory
    {
        return $this->afterMaking(function (Tour $tour) use ($travel) {
            $tour->travel()->associate($travel);
            $tour->endingDate = $tour->startingDate->addDays($travel->numberOfDays);
        });
    }
}
