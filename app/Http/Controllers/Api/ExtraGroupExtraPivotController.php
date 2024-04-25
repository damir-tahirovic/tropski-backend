<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Exception;
use App\Models\Extra;
use App\Models\ExtraGroup;
use App\Models\ExtraGroupExtraPivot;

class ExtraGroupExtraPivotController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/extra-group-extra-pivots",
     *     tags={"ExtraGroupExtraPivot"},
     *     summary="Finds all extra groups and pivots",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="extra-group-extra-pivots.index",
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
        $extraGroupExtraPivot = ExtraGroupExtraPivot::all();
        return response()->json(['extraGroupExtraPivot' => $extraGroupExtraPivot]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'extra_group_id' => 'required',
                'extra_id' => 'required',
                'price' => 'required',
                'quantity' => 'required|max:9999',
                'unit' => 'required'
            ]);
            $extra = Extra::findOrFail($validated['extra_id']);
            $extraGroup = ExtraGroup::findOrFail($validated['extra_group_id']);
            $extraGroupExtraPivot = ExtraGroupExtraPivot::create($validated);
            return response()->json(['extraGroupExtraPivot' => $extraGroupExtraPivot]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $extraGroupExtraPivot = ExtraGroupExtraPivot::findOrFail($id);
            return response()->json(['extraGroupExtraPivot' => $extraGroupExtraPivot]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'extra_group_id' => 'required',
                'extra_id' => 'required',
                'price' => 'required',
                'quantity' => 'required|max:9999',
                'unit' => 'required'
            ]);
            $extra = Extra::findOrFail($validated['extra_id']);
            $extraGroup = ExtraGroup::findOrFail($validated['extra_group_id']);
            $extraGroupExtraPivot = ExtraGroupExtraPivot::findOrFail($id);
            $extraGroupExtraPivot->update($validated);
            return response()->json(['extraGroupExtraPivot' => $extraGroupExtraPivot]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $extraGroupExtraPivot = ExtraGroupExtraPivot::findOrFail($id);
            $extraGroupExtraPivot->delete();
            return response()->json(['extraGroupExtraPivot' => $extraGroupExtraPivot]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
