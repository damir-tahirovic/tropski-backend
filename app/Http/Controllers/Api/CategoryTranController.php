<?php

namespace App\Http\Controllers\Api;

use App\Models\CategoryTran;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

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
        //        try {
//            $this->authorize('view', CategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//
        try {
            $categoryTrans = CategoryTran::all();
            return response()->json(['categoryTrans' => $categoryTrans]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/category-trans",
     *     tags={"CategoryTran"},
     *     summary="Create a new category translation",
     *     operationId="category-trans.store",
     *     @OA\RequestBody(
     *         description="Category translation data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CategoryTran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category translation created successfully"
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
//            $this->authorize('create', CategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
        $validated = $request->validate([
            'category_id' => 'required',
            'lang_id' => 'required',
            'name' => 'required|max:255',
        ]);
            $category = Category::findOrFail($request->input('category_id'));
            $lang = Language::findOrFail($request->input('lang_id'));
            $categoryTran = CategoryTran::create($request->all());
            return response()->json(['categoryTrans' => $categoryTran], '201');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function categoryNamesWithLanguages()
    {
        try {
            $result = DB::table('categories as c')
                ->join('category_trans as ct', 'ct.category_id', '=', 'c.id')
                ->join('languages as l', 'ct.lang_id', '=', 'l.id')
                ->select('l.code', 'c.id as category_id', 'ct.name as category_name')
                ->get();
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/category-trans/{id}",
     *     tags={"CategoryTran"},
     *     summary="Find category translation by ID",
     *     description="Returns a single category translation",
     *     operationId="category-trans.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category translation to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid category translation ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category translation not found"
     *     )
     * )
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', CategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $categoryTran = CategoryTran::findOrFail($id);
            return response()->json(['categoryTrans' => $categoryTran]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }

    /**
     * @OA\Put(
     *     path="/api/category-trans/{id}",
     *     tags={"CategoryTran"},
     *     summary="Update an existing category translation",
     *     description="",
     *     operationId="category-trans.update",
     *     @OA\RequestBody(
     *         description="Category translation object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CategoryTran")
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
     *         description="Category translation not found"
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
//            $this->authorize('update', CategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
        $categoryTran = CategoryTran::findOrFail($id);
        $validated = $request->validate([
            'category_id' => 'required',
            'lang_id' => 'required',
            'name' => 'required|max:255',
        ]);
            $category = Category::findOrFail($request->input('category_id'));
            $lang = Language::findOrFail($request->input('lang_id'));
            $categoryTran->update($request->all());
            return response()->json(['categoryTrans' => $categoryTran], '200');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/category-trans/{id}",
     *     tags={"CategoryTran"},
     *     summary="Deletes a category translation",
     *     description="",
     *     operationId="category-trans.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category translation id to delete",
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
     *         description="Category translation not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', CategoryTran::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $categoryTran = CategoryTran::findOrFail($id);
            return response()->json(['categoryTrans' => $categoryTran]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

}
