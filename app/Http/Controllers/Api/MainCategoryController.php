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

    protected $categoryController;

    public function __construct(CategoryController $categoryController)
    {
        $this->categoryController = $categoryController;
    }

//    public function getMainCategoryWithCategories($mainCategory)
//    {
//        $categories = $mainCategory->categories;
//
//        if ($categories !== null && $categories->isNotEmpty()) {
//            $mainCategory->categories = $categories->map(function ($category) {
//                return $this->categoryController->getCategoryWithSubcategories($category);
//            });
//        }
//        return $mainCategory;
//    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required',
                'name_en' => 'required',
                'name_me' => 'required'
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

            //Prevod za engleski jezik
            $mainCategoryTran1 = MainCategoryTran::create([
                'main_cat_id' => $mainCategory->id,
                'name' => $validated['name_en'],
                'lang_id' => '2'
            ]);

            //Prevod za crnogorski jezik
            $mainCategoryTran2 = MainCategoryTran::create([
                'main_cat_id' => $mainCategory->id,
                'name' => $validated['name_me'],
                'lang_id' => '1'
            ]);

            return response()->json(['mainCategory' => $mainCategory,
                'mainCategoryTran1' => $mainCategoryTran1,
                'mainCategoryTran2' => $mainCategoryTran2], '201');
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
        $mainCategory = MainCategory::findOrFail($id);

        // Get all categories with subcategories
        $categoriesWithSubcategoriesResponse = $this->categoryController->index();
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
            $categoriesWithSubcategoriesResponse = $this->categoryController->index();
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
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
