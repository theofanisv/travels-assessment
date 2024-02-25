<?php

namespace Database\Factories;

use App\Enums\Mood;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name'         => ($name = str($this->faker->unique()->sentence()))->title()->toString(),
            'slug'         => $name->slug()->toString(),
            'description'  => $this->faker->paragraph(),
            'numberOfDays' => $this->faker->numberBetween(1, 15),
            'isPublic'     => $this->faker->boolean(80),
            'moods'        => collect(Mood::cases())
                ->mapWithKeys(fn(Mood $mood) => [$mood->value => $this->faker->numberBetween(0, 100)])
                ->toArray(),
        ];
    }

    public function withThumbnail(): static
    {
        return $this->afterMaking(function (Travel $travel) {
            $url = $this->faker->imageUrl(1920, 1080, 'thumbnail', true, $travel->slug);
            $travel->addMediaFromUrl($url)
                ->toMediaCollection('thumbnail');
        });
    }

    public function withPhotos(?int $count = null): static
    {
        return $this->afterMaking(function (Travel $travel) use ($count) {
            for ($i = 0; $i < $count ?? rand(1, 5); $i++) {
                $url = $this->faker->imageUrl(1920, 1080, 'photo', true, "#$i {$travel->name}");
                $travel->addMediaFromUrl($url)
                    ->toMediaCollection('photos');
            }
        });
    }
}
