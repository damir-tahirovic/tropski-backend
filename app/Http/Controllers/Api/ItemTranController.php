<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ItemTran;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemTranController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/item-trans",
     *     tags={"ItemTran"},
     *     summary="Finds all items on different languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="item-trans.index",
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
//            $this->authorize('view', ItemTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
        $itemTrans = ItemTran::all();
        return response()->json(['itemTrans' => $itemTrans]);
        }catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/item-trans",
     *     tags={"ItemTran"},
     *     summary="Create a new item translation",
     *     operationId="item-trans.store",
     *     @OA\RequestBody(
     *         description="Item translation data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ItemTran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item translation created successfully"
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
//            $this->authorize('create', ItemTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $validated = $request->validate([
                'item_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required|max:255',
                'description' => 'required'
            ]);
            $lang = Language::findOrFail($request->input('lang_id'));
            $item = Item::findOrFail($request->input('item_id'));
            $itemTran = ItemTran::create($validated);
            return response()->json(['itemTran' => $itemTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/item-trans/{id}",
     *     tags={"ItemTran"},
     *     summary="Find item translation by ID",
     *     description="Returns a single item translation",
     *     operationId="item-trans.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item translation to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid item translation ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item translation not found"
     *     )
     * )
     */
    public
    function show($id)
    {
        //        try {
//            $this->authorize('viewAny', ItemTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $itemTran = ItemTran::findOrFail($id);
            return response()->json(['itemTran' => $itemTran]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/item-trans/{id}",
     *     tags={"ItemTran"},
     *     summary="Update an existing item translation",
     *     description="",
     *     operationId="item-trans.update",
     *     @OA\RequestBody(
     *         description="Item translation object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ItemTran")
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
     *         description="Item translation not found"
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
//            $this->authorize('update', ItemTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $itemTran = ItemTran::findOrFail($id);
            $validated = $request->validate([
                'item_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required|max:255',
                'description' => 'required'
            ]);
            $lang = Language::findOrFail($request->input('lang_id'));
            $item = Item::findOrFail($request->input('item_id'));
            $itemTran->update($validated);
            return response()->json(['itemTran' => $itemTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/item-trans/{id}",
     *     tags={"ItemTran"},
     *     summary="Deletes an item translation",
     *     description="",
     *     operationId="item-trans.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item translation id to delete",
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
     *         description="Item translation not found"
     *     )
     * )
     */
    public
    function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', ItemTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $itemTran = ItemTran::findOrFail($id);
            $itemTran->delete();
            return response()->json(['itemTran' => $itemTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }


}
