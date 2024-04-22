<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExtraController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/extras",
     *     tags={"Extra"},
     *     summary="Finds all Extras",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="extras.index",
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status values that needed to be considered for filter",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="active",
     *             type="string",
     *             enum={"active", "inactive"} 
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index()
    {
        $extra = Extra::with('media')->get();
        return response()->json(['data' => $extra]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required'
            ]);
            $extra = Extra::create($request->all());
            $extra->addMediaFromRequest('image')->toMediaCollection();
            $extra->getMedia();
            return response()->json(['data' => $extra], '201');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $extra = Extra::findOrFail($id);
            $extra->getMedia();
            return response()->json(['data' => $extra]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $extra = Extra::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required'
            ]);
            $extra->update($validated);
            Media::where('model_id', $id)
                ->where('model_type', Extra::class)
                ->delete();
            $extra->addMediaFromRequest('image')->toMediaCollection();
            $extra->getMedia();
            return response()->json(['data' => $extra], '200');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $extra = Extra::findOrFail($id);
            $extra->delete();
            return response()->json(['data' => $extra]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
