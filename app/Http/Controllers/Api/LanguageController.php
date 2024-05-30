<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/languages",
     *     tags={"Language"},
     *     summary="Finds all languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="languages.index",
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
//            $this->authorize('view', Language::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $language = Language::all();
            return response()->json(['languages' => $language]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/languages",
     *     tags={"Language"},
     *     summary="Create a new language",
     *     operationId="languages.store",
     *     @OA\RequestBody(
     *         description="Language data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Language")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Language created successfully"
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
//            $this->authorize('create', Language::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'code' => 'required'
            ]);
            $languages = Language::create($validated);
            return response()->json(['languages' => $languages], '201');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/languages/{id}",
     *     tags={"Language"},
     *     summary="Find language by ID",
     *     description="Returns a single language",
     *     operationId="languages.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of language to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid language ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Language not found"
     *     )
     * )
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', Language::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $language = Language::findOrFail($id);
            return response()->json(['languages' => $language]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/languages/{id}",
     *     tags={"Language"},
     *     summary="Update an existing language",
     *     description="",
     *     operationId="languages.update",
     *     @OA\RequestBody(
     *         description="Language object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Language")
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
     *         description="Language not found"
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
//            $this->authorize('update', Language::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'code' => 'required'
            ]);
            $languages = Language::findOrFail($id);
            $languages = Language::update($validated);
            return response()->json(['languages' => $languages], '201');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/languages/{id}",
     *     tags={"Language"},
     *     summary="Deletes a language",
     *     description="",
     *     operationId="languages.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Language id to delete",
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
     *         description="Language not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', Language::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $languages = Language::findOrFail($id);
            $languages->delete();
            return response()->json(['languages' => $languages], '200');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
