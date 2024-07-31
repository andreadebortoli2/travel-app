<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use GrahamCampbell\ResultType\Success;

use Illuminate\Support\Facades\DB;
use App\Models\Day;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = DB::table('trips')->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'trips' => $trips
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = Trip::where('id', $id)->get();
        $days = Day::where('trip_id', $id)->orderBy('date', 'asc')->get();
        return response([
            'success' => true,
            'trip' => $trip,
            'days' => $days
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        //
    }
}
