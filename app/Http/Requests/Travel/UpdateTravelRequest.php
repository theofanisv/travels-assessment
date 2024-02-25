<?php

namespace App\Http\Requests\Travel;

use App\Enums\Mood;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Auth\Access\Response as AuthResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property Travel $travel via route parameter
 */
class UpdateTravelRequest extends FormRequest
{
    use HandlesTravelMedia;

    public function authorize(): AuthResponse
    {
        return Gate::authorize('update', $this->travel);
    }

    public function rules(): array
    {
        return [
            'name'         => ['exclude_if:name,null', 'string'],
            'slug'         => ['exclude_if:slug,null', 'string', 'alpha_dash:ascii', "unique:travels,slug,{$this->travel->id}"],
            'description'  => ['exclude_if:description,null', 'string'],
            'isPublic'     => ['exclude_if:isPublic,null', 'boolean'],
            'numberOfDays' => ['exclude_if:numberOfDays,null', 'integer', 'gte:1'],
            'moods'        => ['exclude_if:moods,null', 'array:' . Mood::keys()->implode(',')],
            'moods.*'      => ['integer'],
            'thumbnail'    => ['nullable', 'image', 'max:10240'],
            'photos'       => ['nullable', 'array'],
            'photos.*'     => ['image', 'max:10240'],
        ];
    }

    public function handle(): static
    {
        $this->travel->update($this->validated());

        $this->handleMedia();

        // Should add an extra endpoint or parameter for clearing existing photos.
        // $this->travel->clearMediaCollection('photos'); 

        return $this;
    }

    public function getResponse(): TravelResource
    {
        return TravelResource::make($this->travel)
            ->withThumbnail()
            ->withPhotos();
    }
}
