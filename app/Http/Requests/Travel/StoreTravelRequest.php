<?php

namespace App\Http\Requests\Travel;

use App\Enums\Mood;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Auth\Access\Response as AuthResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreTravelRequest extends FormRequest
{
    use HandlesTravelMedia;
    
    public Travel $travel;

    public function authorize(): AuthResponse
    {
        // Prefer `Gate` to render a user-friendly message instead `$this->user()?->can(...)`.
        return Gate::authorize('create', Travel::class);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string'],
            'slug'         => ['required', 'string', 'alpha_dash:ascii', 'unique:travels,slug'], // Should we leave nullable and auto-generate?
            'description'  => ['nullable', 'string'],
            'isPublic'     => ['nullable', 'boolean'],
            'numberOfDays' => ['required', 'integer', 'gte:1'],
            'moods'        => ['required', 'array:' . Mood::keys()->implode(',')],
            'moods.*'      => ['integer'],
            'thumbnail'    => ['nullable', 'image', 'max:10240'],
            'photos'       => ['nullable', 'array'],
            'photos.*'     => ['image', 'max:10240'],
        ];
    }

    public function handle(): static
    {
        $this->travel = Travel::create($this->validated());

        $this->handleMedia();

        return $this;
    }

    public function getResponse(): TravelResource
    {
        return TravelResource::make($this->travel)
            ->withThumbnail()
            ->withPhotos();
    }
}
