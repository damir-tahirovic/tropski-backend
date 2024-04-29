<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ExtraGroup;
use App\Models\Item;
use App\Models\ItemTran;
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
        $items = Item::with('media')->get();
        return response()->json(['items' => $items]);
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
        try {
            $validated = $request->validate([
                'image' => 'required',
                'category_id' => 'required',
                'extra_group_id' => 'required',
                'code' => 'required',
                'name_en' => 'required|max:255',
                'name_me' => 'required|max:255',
                'description_en' => 'nullable',
                'description_me' => 'nullable'
            ]);
            $category = Category::findOrFail($request->input('category_id'));
            $extraGroup = ExtraGroup::findOrFail($request->input('extra_group_id'));
            $item = Item::create($request->all());
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $item->addMedia($image)->toMediaCollection();
                $item->getMedia();
            }
            //Prevod za engleski jezik
            $itemTran1 = ItemTran::create([
                'item_id' => $item->id,
                'name' => $validated['name_en'],
                'description' => $validated['description_en'],
                'lang_id' => '2'
            ]);

            //Prevod za crnogorski jezik
            $itemTran2 = ItemTran::create([
                'item_id' => $item->id,
                'name' => $validated['name_me'],
                'description' => $validated['description_me'],
                'lang_id' => '1'
            ]);
            return response()->json(['items' => $item,
                'itemTran1' => $itemTran1,
                'itemTran2' => $itemTran2], 201);
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
        try {
            $item = Item::findOrFail($id);
            $item->getMedia();
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
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return response()->json(['item' => $item]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
