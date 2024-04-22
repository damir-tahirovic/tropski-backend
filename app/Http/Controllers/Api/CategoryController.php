<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Finds all categories",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="viewAllCategories",
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
        $categories = Category::all();
        return response()->json($categories);
    }

    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'image' => 'required',
                'main_cat_id' => 'required'
            ]);
            $mainCategory = MainCategory::findOrFail($request->input('main_cat_id'));
            $category = Category::create($validated);
            $category->addMediaFromRequest('image')->toMediaCollection();
            $category->getMedia();
            return response()->json(['data' => $category]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->getMedia();
            return response()->json($category);
        } catch (\Exception $e) {
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'image' => 'required',
                'main_cat_id' => 'required'
            ]);
            $category = Category::findOrFail($id);
            $mainCategory = MainCategory::findOrFail($request->input('main_cat_id'));
            $main_cat_id = $request->input('main_cat_id');
            $category->update(['main_cat_id' => $main_cat_id]);
            Media::where('model_id', 6)->delete();
            $category->addMediaFromRequest('image')->toMediaCollection();
            $category->getMedia();
            return response()->json(['data' => $category]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
