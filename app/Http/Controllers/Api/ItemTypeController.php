<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ItemType;
use App\Models\Item;

class ItemTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/item-types",
     *     tags={"ItemType"},
     *     summary="Finds all item types",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="item-types.index",
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
            $itemTypes = ItemType::all();
            return response()->json(['itemTypes' => $itemTypes]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
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
                'item_id' => 'required',
                'quantity' => 'required',
                'price' => 'required',
                'unit' => 'required',
            ]);
            $item = Item::findOrFail($validated['item_id']);
            $itemType = ItemType::create($request->all());
            return response()->json(['itemType' => $itemType]);
        } catch (Exception $e) {

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
            $itemType = ItemType::findOrFail($id);
            return response()->json(['itemType' => $itemType]);
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
                'item_id' => 'required',
                'quantity' => 'required',
                'price' => 'required',
                'unit' => 'required',
            ]);
            $item = Item::findOrFail($validated['item_id']);
            $itemType = ItemType::findOrFail($id);
            $itemType->update($request->all());
            return response()->json(['itemType' => $itemType]);
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
            $itemType = ItemType::findOrFail($id);
            $itemType->delete();
            return response()->json(['itemType' => $itemType]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
