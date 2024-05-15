<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Extra;
use App\Models\Hotel;
use App\Models\MainCategory;
use App\Models\MainCategoryTran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MainCategoryController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/main-categories",
     *     tags={"MainCategory"},
     *     summary="Create a new main category",
     *     operationId="main-categories.store",
     *     @OA\RequestBody(
     *         description="Main category data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/MainCategory")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Main category created successfully"
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
                'hotel_id' => 'required',
//                'image' => 'required',
            ]);

            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $hotel_id = $hotel->id;
            $mainCategory = MainCategory::create([
                'hotel_id' => $hotel_id,
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $mainCategory->addMedia($image)->toMediaCollection();
                $mainCategory->getMedia();
            }

            foreach ($data['trans'] as $trans) {
                MainCategoryTran::create([
                    'main_cat_id' => $mainCategory->id,
                    'lang_id' => $trans['lang_id'],
                    'name' => $trans['name'],
                ]);
            }

            return response()->json(['mainCategory' => $mainCategory], '201');
        } catch (Exception $e) {
            return response()->json($e->getMessage(), '400');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/main-categories/{id}",
     *     tags={"MainCategory"},
     *     summary="Finds a main category by its ID along with its subcategories",
     *     description="Returns a single main category with its subcategories",
     *     operationId="main-categories.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the main category to return",
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
     *         description="MainCategory not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $mainCategory = MainCategory::with('categories.media')->findOrFail($id);
            $mainCategory->getMedia();
            return response()->json(['mainCategory' => $mainCategory]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/main-categories",
     *     tags={"MainCategory"},
     *     summary="Finds all main categories with its subcategories",
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
        try {
            $mainCategories = MainCategory::with('media')->with('categories')->get();
            return response()->json(['mainCategories' => $mainCategories]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/main-categories/{id}",
     *     tags={"MainCategory"},
     *     summary="Update an existing main category",
     *     description="",
     *     operationId="main-categories.update",
     *     @OA\RequestBody(
     *         description="Main category object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/MainCategory")
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
     *         description="Main category not found"
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
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $mainCategory = MainCategory::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required'
            ]);
            Media::where('model_id', $id)
                ->where('model_type', MainCategory::class)
                ->delete();
            $mainCategory->update($validated);
            $mainCategory->addMediaFromRequest('image')->toMediaCollection();
            $mainCategory->getMedia();
            return response()->json(['mainCategories' => $mainCategory], '200');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/main-categories/{id}",
     *     tags={"MainCategory"},
     *     summary="Deletes a main category",
     *     description="",
     *     operationId="main-categories.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Main category id to delete",
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
     *         description="Main category not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $mainCategory = MainCategory::findOrFail($id);
            $mainCategory->delete();
            return response()->json(['mainCategories' => $mainCategory]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

}
