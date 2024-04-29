<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTran;
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
        $categories = Category::whereNull('category_id')->get();

        $categoriesWithSubcategories = $categories->map(function ($category) {
            return $this->getCategoryWithSubcategories($category);
        });

        return response()->json(['categories' => $categoriesWithSubcategories]);
    }

    public function getCategoryWithSubcategories($category)
    {
        $subcategories = $category->subcategories;

        if ($subcategories !== null && $subcategories->isNotEmpty()) {
            $category->subcategories = $subcategories->map(function ($subcategory) {

                return $this->getCategoryWithSubcategories($subcategory);
            });
        }
        $category->getMedia();
        return $category;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'image' => 'required',
                'main_cat_id' => 'required',
                'name_me' => 'required|max:255',
                'name_en' => 'required|max:255'
            ]);
            $category_id = $request->input('category_id');
            if ($category_id !== null) {
                $parentCategory = Category::findOrFail($category_id);
                $main_cat_id = $parentCategory->main_cat_id;
                $requestData = $request->all();
                $requestData['main_cat_id'] = $main_cat_id;
                $category = Category::create($requestData);
            } else {
                $category = Category::create($request->all());
            }

            //Prevod za engleski jezik
            $categoryTran1 = CategoryTran::create([
                'category_id' => $category->id,
                'name' => $validated['name_en'],
                'lang_id' => '2'
            ]);

            //Prevod za crnogorski jezik
            $categoryTran2 = CategoryTran::create([
                'category_id' => $category->id,
                'name' => $validated['name_me'],
                'lang_id' => '1'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $category->addMedia($image)->toMediaCollection();
                $category->getMedia();
            }
            return response()->json(['category' => $category,
                'categoryTran1' => $categoryTran1,
                'categoryTran2' => $categoryTran2,], 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
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
            $category = Category::findOrFail($id);
            $categoryWithSubcategories = $this->getCategoryWithSubcategories($category);
            return response()->json(['category' => $categoryWithSubcategories]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
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
                'image' => 'required',
                'main_cat_id' => 'required'
            ]);
            $category = Category::findOrFail($id);
            $category_id = $request->input('category_id');

            if ($category_id !== null) {
                $parentCategory = Category::findOrFail($category_id);
                $main_cat_id = $parentCategory->main_cat_id;
                $requestData = $request->all();
                $requestData['main_cat_id'] = $main_cat_id;
                $category->update($requestData);
            } else {
                $category->update($request->all());
            }

            Media::where('model_id', $id)
                ->where('model_type', Category::class)
                ->delete();
            $category->addMediaFromRequest('image')->toMediaCollection();
            $category->getMedia();
            return response()->json(['data' => $category]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
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
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['data' => $category]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
