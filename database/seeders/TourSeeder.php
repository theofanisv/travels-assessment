<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        Travel::all()
            ->each(fn(Travel $travel) => Tour::factory(rand(1, 5))
                ->forTravel($travel)
                ->create()
            );
    }
}
