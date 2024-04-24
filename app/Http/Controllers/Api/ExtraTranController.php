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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
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
