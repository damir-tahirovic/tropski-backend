<?php

namespace App\Http\Controllers\Api;

use App\Models\CategoryTran;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\Language;

class CategoryTranController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/category-trans",
     *     tags={"CategoryTran"},
     *     summary="Finds all categories on different languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="category-trans.index",
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
            $categoryTrans = CategoryTran::all();
            return response()->json(['categoryTrans' => $categoryTrans]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'lang_id' => 'required',
            'name' => 'required|max:255',
        ]);
        try {
            $category = Category::findOrFail($request->input('category_id'));
            $lang = Language::findOrFail($request->input('lang_id'));
            $categoryTran = CategoryTran::create($request->all());
            return response()->json(['categoryTrans' => $categoryTran], '201');
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
            $categoryTran = CategoryTran::findOrFail($id);
            return response()->json(['categoryTrans' => $categoryTran]);
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
        $categoryTran = CategoryTran::findOrFail($id);
        $validated = $request->validate([
            'category_id' => 'required',
            'lang_id' => 'required',
            'name' => 'required|max:255',
        ]);
        try {
            $category = Category::findOrFail($request->input('category_id'));
            $lang = Language::findOrFail($request->input('lang_id'));
            $categoryTran->update($request->all());
            return response()->json(['categoryTrans' => $categoryTran], '200');
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
            $categoryTran = CategoryTran::findOrFail($id);
            return response()->json(['categoryTrans' => $categoryTran]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

}
