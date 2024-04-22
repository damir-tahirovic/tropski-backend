<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtraGroup;
use App\Models\Hotel;
use Illuminate\Http\Request;

class ExtraGroupController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/extra-groups",
     *     tags={"ExtraGorup"},
     *     summary="Finds all Extra groups",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="extra-groups.index",
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
        $extraGroup = ExtraGroup::all();
        return response()->json(['data' => $extraGroup]);
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
                'name' => 'required|max:255'
            ]);
            $extraGroup = ExtraGroup::create($request->all());
            return response()->json(['data' => $extraGroup], '201');
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
            $extraGroup = ExtraGroup::findOrFail($id);
            return response()->json(['data' => $extraGroup]);
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
            $extraGroup = ExtraGroup::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'name' => 'required|'
            ]);
            $extraGroup->update($validated);
            return response()->json(['data' => $extraGroup], '200');
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
        //
    }
}
