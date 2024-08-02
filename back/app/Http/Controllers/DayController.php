<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Http\Requests\StoreDayRequest;
use App\Http\Requests\UpdateDayRequest;

use App\Models\Stop;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreDayRequest $request)
    {
        $validated = $request->validated();

        $day = Day::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Day $day->id created",
            'route' => [
                'day_id' => $day->id,
                'day_date' => $day->date
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $day = Day::where('id', $id)->get();
        $stops = Stop::where('day_id', $id)->orderBy('id', 'asc')->get();
        return response()->json([
            'success' => true,
            'day' => $day,
            'stops' => $stops
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Day $day)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDayRequest $request, $id)
    {
        $validated = $request->all();

        $day = Day::where('id', $id);
        $day->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Day $id updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $day = Day::where('id', $id);
        $stops = Stop::where('day_id', $id);

        $stops->delete();
        $day->delete();

        return response()->json([
            'success' => true,
            'message' => "Trip $id deleted with its days and stops"
        ]);
    }
}
