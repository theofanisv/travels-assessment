<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Travel\StoreTravelRequest;
use App\Http\Requests\Travel\UpdateTravelRequest;
use App\Models\Travel;

class TravelController extends Controller
{
    public function store(StoreTravelRequest $request)
    {
        return $request->handle()->getResponse();
    }

    public function update(UpdateTravelRequest $request, Travel $travel)
    {
        return $request->handle()->getResponse();
    }
    
}
