<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Hotel;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MainCategoryController extends Controller
{

    protected $categoryController;

    public function __construct(CategoryController $categoryController)
    {
        $this->categoryController = $categoryController;
    }

    public function getMainCategoryWithCategories($mainCategory)
    {
        $categories = $mainCategory->categories;

        if ($categories !== null && $categories->isNotEmpty()) {
            $mainCategory->categories = $categories->map(function ($category) {
                return $this->categoryController->getCategoryWithSubcategories($category);
            });
        }
        return $mainCategory;
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
        $mainCategories = MainCategory::all();

        $mainCategoriesWithCategories = $mainCategories->map(function ($mainCategory) {
            // Dohvati sve kategorije sa podkategorijama
            $categoriesWithSubcategoriesResponse = $this->categoryController->categoriesWithSubcategories();
            $categoriesWithSubcategories = $categoriesWithSubcategoriesResponse->getData()->categories;

            // Filtriraj kategorije da se uklone duplikati
            $filteredCategories = collect($categoriesWithSubcategories)->filter(function ($category) use ($mainCategory) {
                return $category->main_cat_id === $mainCategory->id;
            });

            $mainCategory->categories = $filteredCategories;
            $mainCategory->getMedia();

            return $mainCategory;
        });

        return response()->json(['main_categories' => $mainCategoriesWithCategories]);
    }

//    public function mainCategoriesWithNestedCategories()
//    {
//        // Get all main categories with their media
//        $mainCategories = MainCategory::with('media')->get();
//
//        // For each main category, load its categories and their nested subcategories
//        $mainCategories->each(function ($mainCategory) {
//            $mainCategory->load(['categories' => function ($query) {
//                $query->whereNull('category_id')->with('allSubcategories');
//            }]);
//        });
//
//        return response()->json(['main_categories' => $mainCategories]);
//    }


//    /**
//     * @OA\Get(
//     *     path="/api/main-categories",
//     *     tags={"MainCategory"},
//     *     summary="Finds all main categories",
//     *     description="Multiple status values can be provided with comma separated string",
//     *     operationId="main-categories.index",
//     *     @OA\Parameter(
//     *         name="status",
//     *         in="query",
//     *         description="Status values that needed to be considered for filter",
//     *         required=true,
//     *         explode=true,
//     *         @OA\Schema(
//     *             default="active",
//     *             type="string",
//     *             enum={"active", "inactive"}
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=200,
//     *         description="successful operation"
//     *     ),
//     *     @OA\Response(
//     *         response=400,
//     *         description="Invalid status value"
//     *     )
//     * )
//     */
//    public function index()
//    {
//        $mainCategories = MainCategory::with('media')->get();
//        return response()->json(['mainCategories' => $mainCategories]);
//    }


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
        $mainCategory = MainCategory::findOrFail($id);

        // Get all categories with subcategories
        $categoriesWithSubcategoriesResponse = $this->categoryController->categoriesWithSubcategories();
        $categoriesWithSubcategories = $categoriesWithSubcategoriesResponse->getData()->categories;

        // Filter categories to remove duplicates
        $filteredCategories = collect($categoriesWithSubcategories)->filter(function ($category) use ($mainCategory) {
            return $category->main_cat_id === $mainCategory->id;
        });

        $mainCategory->categories = $filteredCategories;
        $mainCategory->getMedia();

        return response()->json(['main_category' => $mainCategory]);
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
            Media::where('model_id', $id)
                ->where('model_type', MainCategory::class)
                ->delete();
            $mainCategory->update($validated);
            $mainCategory->addMediaFromRequest('image')->toMediaCollection();
            $mainCategory->getMedia();
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
