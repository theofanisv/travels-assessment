<?php


use App\Enums\Role as RoleEnum;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->seed();
});

test('create new tour for travel', function () {
    $travel = Travel::first();
    $request_data = [
        'name'         => $this->faker->words(3, true),
        'startingDate' => now()->addDays(rand(1, 10))->toDateString(),
        'decimalPrice' => $this->faker->numerify('####.##'),
    ];
    $db_data = Arr::except($request_data, 'decimalPrice') + [
            'travelId'   => $travel->id,
            'endingDate' => Carbon::make($request_data['startingDate'])->addDays($travel->numberOfDays)->toDateString(),
            'price'      => (int)($request_data['decimalPrice'] * 100),
        ];

    $this->actingAs(getUserByRole(RoleEnum::Admin))
        ->postJson(route('api.travels.tours.store', ['travel' => $travel->id]), $request_data)
        ->assertSuccessful();

    $this->assertDatabaseHas('tours', $db_data);

    foreach ([RoleEnum::Editor, null] as $role) {
        $this->actingAs(getUserByRole($role))
            ->postJson(route('api.travels.tours.store', ['travel' => $travel->id]), $request_data)
            ->assertForbidden();
    }
});

test('index tours by travel', function () {
    $travel = Travel::first();
    Tour::factory(100)->forTravel($travel)->create();
    $travel->loadCount('tours');
    $queryParams = [
        'perPage'   => rand(30, 50),
        'priceFrom' => null,
        'priceTo'   => null,
        'dateFrom'  => null,
        'dateTo'    => null,
        //'sorting'   => ['price' => 'desc'],
    ];

    $this->getJson(route('api.travels.tours.index', ['travel' => $travel->id]) . '?' . http_build_query($queryParams))
        ->assertSuccessful()
        ->assertJsonPath('meta.total', $travel->tours_count)
        ->assertJsonPath('travel.id', $travel->id)
        ->assertJsonCount($queryParams['perPage'], 'data');
});