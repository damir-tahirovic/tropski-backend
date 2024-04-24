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
        $itemTypeTrans = ItemTypeTran::all();
        return response()->json(['itemTypeTrans' => $itemTypeTrans]);
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $itemTypeTran = ItemTypeTran::findOrFail($id);
            return response()->json(['itemTypeTran' => $itemTypeTran]);
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $itemTypeTran = ItemTypeTran::findOrFail($id);
            $itemTypeTran->delete();
            return response()->json(['itemTypeTrans' => $itemTypeTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }


}
