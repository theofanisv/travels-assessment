<?php

use App\Enums\Role as RoleEnum;
use App\Http\Requests\Travel\StoreTravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('store new travel', function () {
    Sanctum::actingAs(getUserByRole(RoleEnum::Admin));
    $data = Travel::factory()->definition();

    $this->postJson(route('api.travels.store'), $data)
        ->assertSuccessful()
        ->assertJson(fn(AssertableJson $json) => $json
            ->whereAll(Arr::except($data, 'isPublic'))
            ->hasAll('id')
            ->etc()
        );

    $this->assertDatabaseHas('travels', Arr::except(Travel::make($data)->getAttributes(), 'moods'));
});

test('authorize storing new travel', function (?RoleEnum $role, int $status) {
    $user = getUserByRole($role);

//    $this->spy(StoreTravelRequest::class)
//        ->shouldReceive('createTravel')->andReturnSelf()
//        ->shouldReceive('getResponse')->andReturn(TravelResource::make(new Travel()));

    $this->actingAs($user)
        ->postJson(route('api.travels.store'), Travel::factory()->definition())
        ->assertStatus($status);
})->with([
    'Admin'    => [RoleEnum::Admin, 201],
    'Editor'   => [RoleEnum::Editor, 403],
    'Customer' => [null, 403],
]);

test('update travel', function () {
    $travel = Travel::factory()->create();
    $data = Travel::factory()->definition();

    $this->actingAs(getUserByRole(RoleEnum::Editor))
        ->putJson(route('api.travels.update', ['travel' => $travel->id]), $data)
        ->assertSuccessful();
});

test('update travel with thumbnail', function () {
    Storage::fake($disk = config('media-library.disk_name'));

    $travel = Travel::factory()->create();
    $file = UploadedFile::fake()->image('travel-thumb.jpg');

    $this->actingAs(getUserByRole(RoleEnum::Editor))
        ->put(route('api.travels.update', ['travel' => $travel->id]), [
            'thumbnail' => $file,
        ])
        ->assertSuccessful();

    $thumbnail = $travel->getFirstMedia('thumbnail');
    expect($thumbnail)->not->toBeNull();
    Storage::disk($disk)
        ->assertExists($thumbnail->getPathRelativeToRoot('mobile'))
        ->assertExists($thumbnail->getPathRelativeToRoot('desktop'));
});

test('update travel with photos', function () {
    Storage::fake($disk = config('media-library.disk_name'));

    $travel = Travel::factory()->create();
    $uploaded_photos = collect(range(1, 5))
        ->map(fn($i) => UploadedFile::fake()->image("travel-photo-$i.jpg"));

    $this->actingAs(getUserByRole(RoleEnum::Editor))
        ->put(route('api.travels.update', ['travel' => $travel->id]), [
            'photos' => $uploaded_photos->all(),
        ])
        ->assertSuccessful();

    $photos = $travel->getMedia('photos');
    expect($photos->count())->toBe($uploaded_photos->count());

    foreach ($uploaded_photos as $uploaded_photo) {
        $photo = $photos->where('file_name', $uploaded_photo->name)->first();
        expect($photo)->not->toBeNull("Not found '{$uploaded_photo->name}'");
        Storage::disk($disk)
            ->assertExists($photo->getPathRelativeToRoot('mobile'))
            ->assertExists($photo->getPathRelativeToRoot('desktop'));
    }
});