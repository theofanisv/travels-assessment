<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Tour;
use App\Models\Travel;
use App\Policies\TourPolicy;
use App\Policies\TravelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Travel::class => TravelPolicy::class,
        Tour::class   => TourPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
