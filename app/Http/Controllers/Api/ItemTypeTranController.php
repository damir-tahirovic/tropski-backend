<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\ItemTypeTran;
use App\Models\ItemType;
use App\Models\Language;
use Illuminate\Http\Response;

class ItemTypeTranController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/item-type-trans",
     *     tags={"ItemTypeTran"},
     *     summary="Finds all main item-types on different languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="item-types-trans.index",
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
        //        try {
//            $this->authorize('view', ItemTypeTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
        $itemTypeTrans = ItemTypeTran::all();
        return response()->json(['itemTypeTrans' => $itemTypeTrans]);
        }catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/item-type-trans",
     *     tags={"ItemTypeTran"},
     *     summary="Create a new item type translation",
     *     operationId="item-types-trans.store",
     *     @OA\RequestBody(
     *         description="Item type translation data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ItemTypeTran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item type translation created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request)
    {
        //        try {
//            $this->authorize('create', ItemTypeTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $validated = $request->validate([
                'item_type_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required|max:255'
            ]);
            $landuage = Language::findOrFail($request->input('lang_id'));
            $itemType = ItemType::findOrFail($request->input('item_type_id'));
            $itemTypeTran = ItemTypeTran::create($validated);
            return response()->json(['itemTypeTran' => $itemTypeTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/item-type-trans/{id}",
     *     tags={"ItemTypeTran"},
     *     summary="Find item type translation by ID",
     *     description="Returns a single item type translation",
     *     operationId="item-types-trans.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item type translation to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid item type translation ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item type translation not found"
     *     )
     * )
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', ItemTypeTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $itemTypeTran = ItemTypeTran::findOrFail($id);
            return response()->json(['itemTypeTran' => $itemTypeTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/item-type-trans/{id}",
     *     tags={"ItemTypeTran"},
     *     summary="Update an existing item type translation",
     *     description="",
     *     operationId="item-types-trans.update",
     *     @OA\RequestBody(
     *         description="Item type translation object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ItemTypeTran")
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
     *         description="Item type translation not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        //        try {
//            $this->authorize('update', ItemTypeTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $itemTypeTran = ItemTypeTran::findOrFail($id);
            $validated = $request->validate([
                'item_type_id' => 'required',
                'language_id' => 'required',
                'name' => 'required|max:255'
            ]);
            $language = Language::findOrFail($validated['language_id']);
            $itemType = ItemType::findOrFail($validated['item_type_id']);
            $itemTypeTran->update($validated);
            return response()->json(['itemTypeTran' => $itemTypeTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/item-type-trans/{id}",
     *     tags={"ItemTypeTran"},
     *     summary="Deletes an item type translation",
     *     description="",
     *     operationId="item-types-trans.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item type translation id to delete",
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
     *         description="Item type translation not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', ItemTypeTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $itemTypeTran = ItemTypeTran::findOrFail($id);
            $itemTypeTran->delete();
            return response()->json(['itemTypeTrans' => $itemTypeTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }


}
