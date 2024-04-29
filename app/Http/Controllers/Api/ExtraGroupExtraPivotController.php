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
     * @OA\Post(
     *     path="/api/extra-group-extra-pivots",
     *     tags={"ExtraGroupExtraPivot"},
     *     summary="Create a new extra group pivot",
     *     operationId="extra-group-extra-pivots.store",
     *     @OA\RequestBody(
     *         description="Extra group pivot data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ExtraGroupExtraPivot")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Extra group pivot created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/extra-group-extra-pivots/{id}",
     *     tags={"ExtraGroupExtraPivot"},
     *     summary="Find extra group pivot by ID",
     *     description="Returns a single extra group pivot",
     *     operationId="extra-group-extra-pivots.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of extra group pivot to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid extra group pivot ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Extra group pivot not found"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/extra-group-extra-pivots/{id}",
     *     tags={"ExtraGroupExtraPivot"},
     *     summary="Update an existing extra group pivot",
     *     description="",
     *     operationId="extra-group-extra-pivots.update",
     *     @OA\RequestBody(
     *         description="Extra group pivot object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ExtraGroupExtraPivot")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Extra group pivot not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/extra-group-extra-pivots/{id}",
     *     tags={"ExtraGroupExtraPivot"},
     *     summary="Deletes an extra group pivot",
     *     description="",
     *     operationId="extra-group-extra-pivots.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Extra group pivot id to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Extra group pivot not found"
     *     )
     * )
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
