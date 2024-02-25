<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tour\IndexToursRequest;
use App\Http\Requests\Tour\StoreTourRequest;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(IndexToursRequest $request, ?Travel $travel)
    {
        return $request->getResponse();
    }

    public function store(StoreTourRequest $request, ?Travel $travel)
    {
        return $request->handle()->getResponse();
    }
}
