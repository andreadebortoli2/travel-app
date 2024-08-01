<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use GrahamCampbell\ResultType\Success;

use Illuminate\Support\Facades\DB;
use App\Models\Day;
use App\Models\Stop;

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
        $validated = $request->validated();

        Trip::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'new trip created'
        ]);
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
    public function update(UpdateTripRequest $request, $id)
    {
        $validated = $request->all();

        $trip = Trip::where('id', $id);
        $trip->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Trip $id updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trip = Trip::where('id', $id);
        $days = Day::where('trip_id', $id);
        $getDays = Day::where('trip_id', $id)->get();
        foreach ($getDays as $day) {
            $stopToDelete = Stop::where('day_id', $day->id);
            $stopToDelete->delete();
        }

        $days->delete();
        $trip->delete();

        return response()->json([
            'success' => true,
            'message' => "Trip $id deleted with its days and stops"
        ]);
    }
}
