<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemTypeTran;
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
            $data = json_decode($request->getContent(), true);

            $validated = $request->validate([
                'item_id' => 'required',
                'quantity' => 'required',
                'unit' => 'required|max:255',
                'price' => 'required|max:255'
            ]);

            $itemType = ItemType::create($validated);

            foreach ($data['trans'] as $trans) {
                ItemTypeTran::create([
                    'item_type_id' => $itemType->id,
                    'name' => $trans['name'],
                    'lang_id' => $trans['lang_id']
                ]);

            }

            return response()->json(['itemTypes' => $itemType], 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function indirectStore(array $data, $itemId)
    {
        //        try {
//            $this->authorize('create', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validatedData = Validator::make($data, [
                'quantity' => 'required',
                'unit' => 'required|max:255',
                'price' => 'required|max:255'
            ])->validate();

            $validatedData['item_id'] = $itemId;

            $itemType = ItemType::create($validatedData);

            foreach ($data['trans'] as $trans) {
                ItemTypeTran::create([
                    'item_type_id' => $itemType->id,
                    'name' => $trans['name'],
                    'lang_id' => $trans['lang_id']
                ]);
            }

            return $itemType;
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function storeWithLatestItem(Request $request)
    {
        //        try {
//            $this->authorize('create', ItemType::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validated = $request->validate([
                'quantity' => 'required',
                'unit' => 'required|max:255',
                'price' => 'required',
                'name_en' => 'required',
                'name_me' => 'required'
            ]);

            $item = Item::latest()->first();
            $itemType = ItemType::create(['item_id' => $item->id,
                $validated]);

            //Prevod za engleski jezik
            $itemTypeTran1 = ItemTypeTran::create([
                'item_type_id' => $itemType->id,
                'name' => $validated['name_en'],
                'lang_id' => '2'
            ]);

            //Prevod za crnogorski jezik
            $itemTypeTran2 = ItemTypeTran::create([
                'item_type_id' => $itemType->id,
                'name' => $validated['name_me'],
                'lang_id' => '1'
            ]);

            return response()->json(['itemTypes' => $itemType,
                'itemTypeTran1' => $itemTypeTran1,
                'itemTypeTran2' => $itemTypeTran2], 201);
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
            $itemType = ItemType::findOrFail($id);
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
