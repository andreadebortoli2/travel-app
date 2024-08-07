<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use App\Http\Requests\StoreStopRequest;
use App\Http\Requests\UpdateStopRequest;
use Illuminate\Support\Facades\Storage;

class StopController extends Controller
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
    public function store(StoreStopRequest $request)
    {
        $validated = $request->all();

        if ($request->has('image')) {
            $image = Storage::put('stops-images', $validated['image']);
            $validated['image'] = $image;
        }

        Stop::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Stop created",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stop = Stop::where('id', $id)->get();

        return response([
            'success' => true,
            'stop' => $stop
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stop $stop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStopRequest $request, $id)
    {
        $validated = $request->all();

        $stop = Stop::find($id);

        if ($request->has('image')) {
            if ($stop->image) {
                Storage::delete($stop->image);
            }
            $image = Storage::put('stops-images', $request['image']);
            $validated['image'] = $image;
        }

        $stop->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Stop $id updated"
        ]);
    }

    /**
     * Remove the image of the specified resource from storage.
     */
    public function delete_image($id)
    {
        $stop = Stop::find($id);

        if ($stop->image) {
            Storage::delete($stop->image);

            $stop->image = null;
            $stop->save();
        }

        return response()->json([
            'success' => true,
            'message' => "Stop $id image deleted"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stop = Stop::find($id);

        if ($stop->image) {
            Storage::delete($stop->image);
        }

        $stop->delete();

        return response()->json([
            'success' => true,
            'message' => "Stop $id deleted"
        ]);
    }
}
