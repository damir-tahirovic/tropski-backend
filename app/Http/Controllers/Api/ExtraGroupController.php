<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtraGroup;
use App\Models\Hotel;
use Exception;
use Illuminate\Http\Request;

class ExtraGroupController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/extra-groups",
     *     tags={"ExtraGroup"},
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
        try {
            $this->authorize('view', ExtraGroup::class);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
        try {
            $extraGroup = ExtraGroup::all();
            return response()->json(['extra_groups' => $extraGroup]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/extra-groups",
     *     tags={"ExtraGroup"},
     *     summary="Create a new extra group",
     *     operationId="extra-groups.store",
     *     @OA\RequestBody(
     *         description="Extra group data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ExtraGroup")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Extra group created successfully"
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
            $this->authorize('create', ExtraGroup::class);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $validated = $request->validate([
                'hotel_id' => 'required',
                'name' => 'required|max:255'
            ]);
            $extraGroup = ExtraGroup::create($request->all());
            return response()->json(['data' => $extraGroup], '201');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/extra-groups/{id}",
     *     tags={"ExtraGroup"},
     *     summary="Find extra group by ID",
     *     description="Returns a single extra group",
     *     operationId="extra-groups.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of extra group to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid extra group ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Extra group not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $this->authorize('viewAny', ExtraGroup::class);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
        try {
            $extraGroup = ExtraGroup::findOrFail($id);
            return response()->json(['data' => $extraGroup]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/extra-groups/{id}",
     *     tags={"ExtraGroup"},
     *     summary="Update an existing extra group",
     *     description="",
     *     operationId="extra-groups.update",
     *     @OA\RequestBody(
     *         description="Extra group object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ExtraGroup")
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
     *         description="Extra group not found"
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
            $this->authorize('update', ExtraGroup::class);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $extraGroup = ExtraGroup::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'name' => 'required|'
            ]);
            $extraGroup->update($validated);
            return response()->json(['data' => $extraGroup], '200');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/extra-groups/{id}",
     *     tags={"ExtraGroup"},
     *     summary="Deletes an extra group",
     *     description="",
     *     operationId="extra-groups.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Extra group id to delete",
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
     *         description="Extra group not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->authorize('forceDelete', ExtraGroup::class);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
        try {
            $extraGroup = ExtraGroup::findOrFail($id);
            $extraGroup->delete();
            return response()->json(['extraGroups' => $extraGroup]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
