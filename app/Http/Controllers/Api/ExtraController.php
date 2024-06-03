<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use App\Models\ExtraTran;
use App\Models\Hotel;
use App\Models\Language;
use App\Policies\ExtraPolicy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExtraController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/extras",
     *     tags={"Extra"},
     *     summary="Finds all Extras",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="extras.index",
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
//            $this->authorize('viewAny', Extra::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $extra = Extra::with('media')->get();
            return response()->json(['data' => $extra]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/extras",
     *     tags={"Extra"},
     *     summary="Create a new extra",
     *     operationId="extras.store",
     *     @OA\RequestBody(
     *         description="Extra data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Extra")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Extra created successfully"
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
//            $this->authorize('create', Extra::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $data = json_decode($request->getContent(), true);

            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'nullable',
            ]);

            $extra = Extra::create([
                'hotel_id' => $request->input('hotel_id'),
            ]);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extra->addMedia($image)->toMediaCollection();
                $extra->getMedia();
            }

            foreach ($data['trans'] as $tran) {
                $langId = Language::where('code', $tran['lang_code'])->first()->id;
                ExtraTran::create([
                    'extra_id' => $extra->id,
                    'lang_id' => $langId,
                    'name' => $tran['name'],
                ]);
            }

            return response()->json([
                'extra' => $extra], '201');
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/extras/{id}",
     *     tags={"Extra"},
     *     summary="Find extra by ID",
     *     description="Returns a single extra",
     *     operationId="extras.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of extra to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid extra ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Extra not found"
     *     )
     * )
     */
    public function show($id)
    {
//        try {
//            $this->authorize('view', Extra::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $extra = Extra::findOrFail($id);
            $extra->getMedia();
            return response()->json(['data' => $extra]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/extras/{id}",
     *     tags={"Extra"},
     *     summary="Update an existing extra",
     *     description="",
     *     operationId="extras.update",
     *     @OA\RequestBody(
     *         description="Extra object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Extra")
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
     *         description="Extra not found"
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
//            $this->authorize('update', Extra::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $extra = Extra::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required'
            ]);
            $extra->update($validated);
            Media::where('model_id', $id)
                ->where('model_type', Extra::class)
                ->delete();
            $extra->addMediaFromRequest('image')->toMediaCollection();
            $extra->getMedia();
            return response()->json(['data' => $extra], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/extras/{id}",
     *     tags={"Extra"},
     *     summary="Deletes an extra",
     *     description="",
     *     operationId="extras.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Extra id to delete",
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
     *         description="Extra not found"
     *     )
     * )
     */
    public function destroy($id)
    {
//        try {
//            $this->authorize('forceDelete', Extra::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
//        }
        try {
            $extra = Extra::findOrFail($id);
            $extra->delete();
            return response()->json(['data' => $extra]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
