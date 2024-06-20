<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemTypeTran;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ItemType;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;

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
        //        try {
//            $this->authorize('view', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $itemTypes = ItemType::all();
            return response()->json(['itemTypes' => $itemTypes]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/item-types",
     *     tags={"ItemType"},
     *     summary="Create a new item type",
     *     operationId="item-types.store",
     *     @OA\RequestBody(
     *         description="Item type data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ItemType")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item type created successfully"
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
//            $this->authorize('create', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $trans = json_decode($request->input('trans'), true);

            $validated = $request->validate([
                'item_id' => 'required',
                'quantity' => 'required',
                'unit' => 'required|max:255',
                'price' => 'required|max:255'
            ]);

            $itemType = ItemType::create($validated);

            foreach ($trans as $tran) {
                ItemTypeTran::create([
                    'item_type_id' => $itemType->id,
                    'name' => $tran['name'],
                    'lang_id' => $tran['lang_id']
                ]);

            }

            return response()->json(['itemTypes' => $itemType], 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function indirectStore(array $type, $itemId)
    {
        //        try {
//            $this->authorize('create', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validatedData = Validator::make($type, [
                'quantity' => 'required',
                'unit' => 'required|max:255',
                'price' => 'required|max:255'
            ])->validate();

            $validatedData['item_id'] = $itemId;

            $itemType = ItemType::create($validatedData);

            foreach ($type['trans'] as $tran) {
                $lang_id = Language::where('code', $tran['lang_code'])->first()->id;
                ItemTypeTran::create([
                    'item_type_id' => $itemType->id,
                    'name' => $tran['name'],
                    'lang_id' => $lang_id
                ]);
            }

            return $itemType;
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/item-types/{id}",
     *     tags={"ItemType"},
     *     summary="Find item type by ID",
     *     description="Returns a single item type",
     *     operationId="item-types.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item type to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid item type ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item type not found"
     *     )
     * )
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $itemType = ItemType::with([
                'item',
                'itemTypeTrans',
                'itemTypeTrans.languages'
            ])->findOrFail($id);
            return response()->json(['itemType' => $itemType]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/item-types/{id}",
     *     tags={"ItemType"},
     *     summary="Update an existing item type",
     *     description="",
     *     operationId="item-types.update",
     *     @OA\RequestBody(
     *         description="Item type object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ItemType")
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
     *         description="Item type not found"
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
//            $this->authorize('uodate', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validated = $request->validate([
                'item_id' => 'required',
                'quantity' => 'required',
                'price' => 'required',
                'unit' => 'required',
                'trans' => 'required'
            ]);
            $item = Item::findOrFail($validated['item_id']);
            $itemType = ItemType::findOrFail($id);
            $itemType->update($validated);
            $trans = json_decode($request->input('trans'));
            foreach ($trans as $tran){
                $itemTypeTran = ItemTypeTran::where('item_type_id', $itemType->id)->where('lang_id', $tran->lang_id)->first();
                $itemTypeTran->update([
                    'name' => $tran->name
                ]);
            }
            return response()->json(['item_type' => $itemType]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/item-types/{id}",
     *     tags={"ItemType"},
     *     summary="Deletes an item type",
     *     description="",
     *     operationId="item-types.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item type id to delete",
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
     *         description="Item type not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $itemType = ItemType::findOrFail($id);
            $itemType->delete();
            return response()->json(['itemType' => $itemType]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
