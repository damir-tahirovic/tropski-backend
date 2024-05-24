<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTran;
use App\Models\Language;
use App\Models\MainCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Finds all categories with its subcategories",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="categories.index",
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
            $categories = Category::with('media')->with('items')->get();


            return response()->json(['categories' => $categories]);

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Create a new category",
     *     operationId="categories.store",
     *     @OA\RequestBody(
     *         description="Category data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully"
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
            $data = json_decode($request->getContent(), true);

            $validated = $request->validate([
                //'image' => 'required',
                'main_cat_id' => 'required'
            ]);

            MainCategory::findOrFail($request->input('main_cat_id'));
            $category = Category::create($validated);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $category->addMedia($image)->toMediaCollection();
                $category->getMedia();
            }

            foreach ($data['trans'] as $tran) {
                $lang_id = Language::where('code', $tran['lang_code'])->first()->id;
                CategoryTran::create([
                    'category_id' => $category->id,
                    'name' => $tran['name'],
                    'lang_id' => $lang_id
                ]);
            }

            return response()->json(['category' => $category], 201);

        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     summary="Find category by ID",
     *     description="Returns a single category",
     *     operationId="categories.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid category ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->getMedia();
            return response()->json(['category' => $category]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     summary="Update an existing category",
     *     description="",
     *     operationId="categories.update",
     *     @OA\RequestBody(
     *         description="Category object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Category")
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
     *         description="Category not found"
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
            $data = json_decode($request->getContent(), true);

            $validated = $request->validate([
//                'image' => 'required',
                'main_cat_id' => 'required'
            ]);
            $category = Category::findOrFail($id);

            Media::where('model_id', $id)
                ->where('model_type', Category::class)
                ->delete();

            $category->update($validated);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $category->addMedia($image)->toMediaCollection();
                $category->getMedia();
            }

            foreach ($data['trans'] as $tran) {
                $language = Language::where('code', $tran['lang_code'])->first();
                $categoryTran = CategoryTran::where('category_id', $category->id)->where('lang_id', $language->id)->first();
                if ($categoryTran) {
                    $categoryTran->update([
                        'category_id' => $category->id,
                        'name' => $tran['name'],
                        'lang_id' => $language->id
                    ]);
                } else {
                    CategoryTran::create([
                        'category_id' => $category->id,
                        'name' => $tran['name'],
                        'lang_id' => $language->id
                    ]);
                }
            }

            $category->getMedia();
            return response()->json(['category' => $category]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     summary="Deletes a category",
     *     description="",
     *     operationId="categories.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category id to delete",
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
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['data' => $category]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
