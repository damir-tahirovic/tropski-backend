<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ExtraGroup;
use App\Models\Item;
use App\Models\ItemTran;
use App\Models\ItemType;
use App\Models\ItemTypeTran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ItemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/items",
     *     tags={"Item"},
     *     summary="Finds all items",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="items.index",
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
//            $this->authorize('view', Item::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $items = Item::with(['media',
                'itemTrans',
                'itemTypes',
                'itemTypes.itemTypeTrans'])->get();
            return response()->json(['items' => $items]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

    }

    public function itemsByCategory($categoryId)
    {
//        try {
//            $this->authorize('viewAny', Item::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $category = Category::findOrFail($categoryId);
            $category->load('media');
            $category->load('items.media');
            return response()->json(["category" => $category]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/items",
     *     tags={"Item"},
     *     summary="Creates a new item",
     *     description="Creates a new item with the provided data",
     *     operationId="items.store",
     *     @OA\RequestBody(
     *         description="Item object that needs to be added",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="extra_group_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="code",
     *                     type="string"
     *                 ),
     *                 example={"image": "image.jpg", "category_id": 1, "extra_group_id": 1, "code": "ITEM001"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
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
//            $this->authorize('create', Item::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {

            $validated = $request->validate([
                'category_id' => 'required',
                'code' => 'required',
                'description' => 'nullable',
                'item_types' => 'required',
                'item_trans' => 'required',
            ]);
            $category = Category::findOrFail($request->input('category_id'));
            $item = Item::create($request->all());
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $item->addMedia($image)->toMediaCollection();
                $item->getMedia();
            }

            $data = json_decode($request->input(), true);

            if (count($data['types']) == 1) {
                $itemType = ItemType::create([
                    'item_id' => $item->id,
                    'quantity' => $data['types'][0]['quantity'],
                    'unit' => $data['types'][0]['unit'],
                    'price' => $data['types'][0]['price'],
                ]);
                foreach ($data['trans'] as $tran) {
                    ItemTran::create([
                        'item_id' => $item->id,
                        'lang_id' => $tran['lang_id'],
                        'name' => $tran['name'],
                        'description' => $tran['description'],
                    ]);

                    ItemTypeTran::create([
                        'item_type_id' => $itemType->id,
                        'name' => $tran['name'],
                        'lang_id' => $tran['lang_id']
                    ]);

                }
            } else {
                foreach ($data['trans'] as $tran) {
                    ItemTran::create([
                        'item_id' => $item->id,
                        'lang_id' => $tran['lang_id'],
                        'name' => $tran['name'],
                        'description' => $tran['description'],
                    ]);
                }
                $itemTypeController = new ItemTypeController();
                foreach ($data['types'] as $type) {
                    $itemTypeController->indirectStore($type, $item->id);
                }
            }

            return response()->json(['items' => $item], 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/items/{id}",
     *     tags={"Item"},
     *     summary="Finds an item by its ID",
     *     description="Returns a single item",
     *     operationId="items.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the item to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
     *     )
     * )
     */
    public function show($id)
    {
//        try {
//            $this->authorize('viewAny', Item::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $item = Item::with([
                'media',
                'itemTrans',
                'itemTrans.languages',
                'itemTypes',
                'itemTypes.itemTypeTrans',
                'itemTypes.itemTypeTrans.languages'
                ])->findOrFail($id);
            return response()->json(['item' => $item]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/items/{id}",
     *     tags={"Item"},
     *     summary="Update an existing item",
     *     description="",
     *     operationId="items.update",
     *     @OA\RequestBody(
     *         description="Item object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Item")
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
     *         description="Item not found"
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
//            $this->authorize('update', Item::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validated = $request->validate([
                'image' => 'required',
                'category_id' => 'required',
                'extra_group_id' => 'required',
                'code' => 'required'
            ]);
            $item = Item::findOrFail($id);
            $category = Category::findOrFail($request->input('category_id'));
            $extraGroup = ExtraGroup::findOrFail($request->input('extra_group_id'));
            Media::where('model_id', $id)
                ->where('model_type', Item::class)
                ->delete();
            $item->addMediaFromRequest('image')->toMediaCollection();
            $item->getMedia();
            return response()->json(['item' => $item]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/items/{id}",
     *     tags={"Item"},
     *     summary="Deletes an item",
     *     description="",
     *     operationId="items.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item id to delete",
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
     *         description="Item not found"
     *     )
     * )
     */
    public function destroy($id)
    {
//        try {
//            $this->authorize('forceDelete', Item::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return response()->json(['item' => $item]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
