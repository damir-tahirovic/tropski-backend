<?php

namespace App\Http\Controllers\Api;

use App\Models\MainCategory;
use App\Http\Controllers\Controller;
use App\Models\MainCategoryTran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Language;

class MainCategoryTranController extends Controller
{
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
        $mainCategoryTrans = MainCategoryTran::all();
        return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $mainCategoryTrans = MainCategoryTran::findOrFail($id);
            return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $mainCategoryTrans = MainCategoryTran::findOrFail($id);
            $mainCategoryTrans->delete();
            return response()->json(['mainCategoryTrans' => $mainCategoryTrans]);
        }
        catch (Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
