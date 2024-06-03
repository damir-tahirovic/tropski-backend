<?php

namespace App\Http\Controllers\Api;

use App\Models\MainCategory;
use App\Http\Controllers\Controller;
use App\Models\MainCategoryTran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class MainCategoryTranController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/main-category-trans-languages",
     *     tags={"MainCategoryTran"},
     *     summary="Finds all main categories names with its languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="main-category-trans.mainCategoryNamesWithLanguages",
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
    public function mainCategoryNamesWithLanguages()
    {
        //        try {
//            $this->authorize('view', MainCategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $result = DB::table('main_categories as mc')
                ->join('main_category_trans as mct', 'mct.main_cat_id', '=', 'mc.id')
                ->join('languages as l', 'mct.lang_id', '=', 'l.id')
                ->select('l.code', 'mc.id as main_category_id', 'mct.name as main_category_name')
                ->get();
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/main-category-trans",
     *     tags={"MainCategoryTran"},
     *     summary="Finds all main categories on different languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="main-category-trans.index",
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
//            $this->authorize('view', MainCategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
        $mainCategoryTrans = MainCategoryTran::all();
        return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);
        }catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/main-category-trans",
     *     tags={"MainCategoryTran"},
     *     summary="Create a new main category translation",
     *     operationId="main-category-trans.store",
     *     @OA\RequestBody(
     *         description="Main category translation data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/MainCategoryTran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Main category translation created successfully"
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
//            $this->authorize('create', MainCategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validated = $request->validate([
                'main_cat_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required'
            ]);
            $language = Language::findOrFail($request->input('lang_id'));
            $mainCategory = MainCategory::findOrFail($request->input('main_cat_id'));
            $mainCategoryTrans = MainCategoryTran::create($request->all());
            return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/main-category-trans/{id}",
     *     tags={"MainCategoryTran"},
     *     summary="Find main category translation by ID",
     *     description="Returns a single main category translation",
     *     operationId="main-category-trans.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of main category translation to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid main category translation ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Main category translation not found"
     *     )
     * )
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', MainCategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $mainCategoryTrans = MainCategoryTran::findOrFail($id);
            return response()->json(['mainCategoryTrans' => $mainCategoryTrans,]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/main-category-trans/{id}",
     *     tags={"MainCategoryTran"},
     *     summary="Update an existing main category translation",
     *     description="",
     *     operationId="main-category-trans.update",
     *     @OA\RequestBody(
     *         description="Main category translation object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/MainCategoryTran")
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
     *         description="Main category translation not found"
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
//            $this->authorize('update', MainCategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $mainCategoryTrans = MainCategoryTran::findOrFail($id);
            $validated = $request->validate([
                'main_cat_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required'
            ]);
            $language = Language::findOrFail($request->input('lang_id'));
            $mainCategory = MainCategory::findOrFail($request->input('main_cat_id'));
            $mainCategoryTrans = MainCategoryTran::create($request->all());
            return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/main-category-trans/{id}",
     *     tags={"MainCategoryTran"},
     *     summary="Deletes a main category translation",
     *     description="",
     *     operationId="main-category-trans.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Main category translation id to delete",
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
     *         description="Main category translation not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', MainCategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $mainCategoryTrans = MainCategoryTran::findOrFail($id);
            $mainCategoryTrans->delete();
            return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
