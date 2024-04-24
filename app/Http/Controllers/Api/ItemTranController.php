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
        $itemTrans = ItemTran::all();
        return response()->json(['itemTrans' => $itemTrans]);
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public
    function show($id)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public
    function destroy($id)
    {
        try {
            $itemTran = ItemTran::findOrFail($id);
            $itemTran->delete();
            return response()->json(['itemTran' => $itemTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }


}
