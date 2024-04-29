<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtraTran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExtraTranController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/extra-trans",
     *     tags={"ExtraTran"},
     *     summary="Finds all extras on different languages",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="extra-trans.index",
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
        $extraTran = ExtraTran::all();
        return response()->json(['extraTran' => $extraTran]);
    }

    /**
     * @OA\Post(
     *     path="/api/extra-trans",
     *     tags={"ExtraTran"},
     *     summary="Create a new extra translation",
     *     operationId="extra-trans.store",
     *     @OA\RequestBody(
     *         description="Extra translation data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ExtraTran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Extra translation created successfully"
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
            $validated = $request->validate([
                'extra_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required|max:255',
            ]);
            $extra = Extra::findOrFail($validated['extra_id']);
            $language = Language::findOrFail($validated['lang_id']);
            $extraTran = ExtraTran::create($validated);
            return response()->json(['extraTran' => $extraTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/extra-trans/{id}",
     *     tags={"ExtraTran"},
     *     summary="Find extra translation by ID",
     *     description="Returns a single extra translation",
     *     operationId="extra-trans.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of extra translation to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid extra translation ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Extra translation not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $extraTran = ExtraTran::findOrFail($id);
            return response()->json(['extraTran' => $extraTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/extra-trans/{id}",
     *     tags={"ExtraTran"},
     *     summary="Update an existing extra translation",
     *     description="",
     *     operationId="extra-trans.update",
     *     @OA\RequestBody(
     *         description="Extra translation object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ExtraTran")
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
     *         description="Extra translation not found"
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
            $extraTran = ExtraTran::findOrFail($id);
            $validated = $request->validate([
                'extra_id' => 'required',
                'lang_id' => 'required',
                'name' => 'required|max:255',
            ]);
            $extra = Extra::findOrFail($validated['extra_id']);
            $language = Language::findOrFail($validated['lang_id']);
            $extraTran->update($validated);
            return response()->json(['extraTran' => $extraTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/extra-trans/{id}",
     *     tags={"ExtraTran"},
     *     summary="Deletes an extra translation",
     *     description="",
     *     operationId="extra-trans.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Extra translation id to delete",
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
     *         description="Extra translation not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $extraTran = ExtraTran::findOrFail($id);
            $extraTran->delete();
            return response()->json(['extraTran' => $extraTran]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
