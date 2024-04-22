<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCategoryController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/main-categories-all",
     *     tags={"MainCategory"},
     *     summary="Finds all main categories with its subcategories",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="main-categories.mainCategoriesWithCategories",
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
    public function mainCategoriesWithCategories()
    {
        $mainCategories = MainCategory::with('categories')->get();
        return response()->json(['mainCategories' => $mainCategories]);
    }

    /**
     * @OA\Get(
     *     path="/api/main-categories",
     *     tags={"MainCategory"},
     *     summary="Finds all main categories",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="main-categories.index",
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
        $mainCategories = MainCategory::with('media')->get();
        return response()->json(['mainCategories' => $mainCategories]);
    }


    public function store(Request $request)
    {
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $validated = $request->validate([
                'image' => 'required'

            ]);
            $mainCategory = MainCategory::create($request->all());
            $mainCategory->addMediaFromRequest('image')->toMediaCollection();
            $mainCategory->getMedia();
            return response()->json(['mainCategories' => $mainCategory], '201');
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
            $mainCategory = MainCategory::findOrFail($id);
            $mainCategory->getMedia();
            return response()->json(['mainCategories' => $mainCategory]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
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
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $mainCategory = MainCategory::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required'
            ]);
            $mainCategory->update($validated);
            $mainCategory->addMediaFromRequest('image')->toMediaCollection();
            return response()->json(['mainCategories' => $mainCategory], '200');
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
        try {
            $mainCategory = MainCategory::findOrFail($id);
            $mainCategory->delete();
            return response()->json(['mainCategories' => $mainCategory]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
