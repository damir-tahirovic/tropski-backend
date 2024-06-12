<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelLanguage;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class HotelController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/hotels-main-categories",
     *     tags={"Hotel"},
     *     summary="Finds all hotels with its main categories",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="hotels.allHotelsWithMainCategories",
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
    public function allHotelsWithMainCategories()
    {
        //        try {
//            $this->authorize('view', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotels = Hotel::with('mainCategories')->get();
            $hotels->load('media');
            return response()->json($hotels);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/hotels-main-categories/{id}",
     *     tags={"Hotel"},
     *     summary="Finds hotel with its main categories",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="hotels.hotelsWithMainCategories",
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

    public function hotelsWithMainCategories($id)
    {
        //        try {
//            $this->authorize('view', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->mainCategories;
            return response()->json($hotel);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotel"},
     *     summary="Finds all hotels",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="hotels.index",
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
//            $this->authorize('view', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 409);
//        }
        try {
            $hotels = Hotel::all();
            $hotels->load('media');
            return response()->json($hotels);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public
    function allHotelsWithLanguages()
    {
        //        try {
//            $this->authorize('view', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotels = Hotel::with('languages')->get();
            $hotels->load('media');
            return response()->json($hotels);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/hotels",
     *     tags={"Hotel"},
     *     summary="Create a new hotel",
     *     description="Create a new hotel with the provided data",
     *     operationId="createHotel",
     *     @OA\RequestBody(
     *         description="Hotel data",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Hotel")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Hotel created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public
    function store(Request $request)
    {
        //        try {
//            $this->authorize('create', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {

            $langs = json_decode($request->input('languages'), true);

            $validated = $request->validate([
                "name" => "required|max:255",
                "description" => "nullable",
                "primary_color" => "required",
                "primary_color_light" => "required",
                "primary_color_dark" => "required",
                "secondary_color" => "required",
                "secondary_color_light" => "required",
                "secondary_color_dark" => "required",
                "banner_text" => "nullable",
                "image" => "nullable",
                "banner_image" => "nullable",
                "logo" => "nullable",
                'languages' => 'required'
            ]);

            $hotel = Hotel::create($validated);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $hotel->addMedia($image)->toMediaCollection('images');
                $hotel->getMedia();
            }
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $hotel->addMedia($image)->toMediaCollection('banners');
                $hotel->getMedia();
            }
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $hotel->addMedia($image)->toMediaCollection('logos');
                $hotel->getMedia();
            }


            foreach ($langs as $lang) {
                $lang_id = Language::where('code', $lang['code'])->first()->id;
                HotelLanguage::create([
                    'hotel_id' => $hotel->id,
                    'lang_id' => $lang_id
                ]);
            }

            return response()->json(['hotel' => $hotel], '201');
        } catch (Exception $e) {
            return response()->json($e->getMessage(), '400');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{id}",
     *     tags={"Hotel"},
     *     summary="Find hotel by ID",
     *     description="Returns a single hotel",
     *     operationId="getHotelById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of hotel to return",
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
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hotel not found"
     *     )
     * )
     */
    public
    function show($id)
    {
        //        try {
//            $this->authorize('viewAny', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->getMedia();
            return response()->json($hotel);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/hotels/{id}",
     *     tags={"Hotel"},
     *     summary="Updates a hotel with new data",
     *     description="Updates a hotel with new data",
     *     operationId="updateHotel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of hotel to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Updated hotel data",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Hotel")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hotel updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hotel not found"
     *     )
     * )
     */
    public
    function update(Request $request, $id)
    {
        //        try {
//            $this->authorize('update', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotel = Hotel::findOrFail($id);

            $oldLangs = HotelLanguage::where('hotel_id', $hotel->id)->pluck('lang_id')->toArray();

            $newLangs = collect(json_decode($request->input('languages'), true))->pluck('code')->toArray();

            $validated = $request->validate([
                "name" => "required|max:255",
                "description" => "nullable",
                "primary_color" => "required",
                "primary_color_light" => "required",
                "primary_color_dark" => "required",
                "secondary_color" => "required",
                "secondary_color_light" => "required",
                "secondary_color_dark" => "required",
                "banner_text" => "nullable",
                "image" => "nullable",
                "banner_image" => "nullable",
                "logo" => "nullable",
                'languages' => 'required'
            ]);

            $hotel->update($validated);

            if ($request->hasFile('image')) {
                $hotel->getMedia('images')->first()->delete();
                $image = $request->file('image');
                $hotel->addMedia($image)->toMediaCollection();
                $hotel->getMedia();
            }
            if ($request->hasFile('banner_image')) {
                $hotel->getMedia('banners')->first()->delete();
                $image = $request->file('banner_image');
                $hotel->addMedia($image)->toMediaCollection();
                $hotel->getMedia();
            }
            if ($request->hasFile('logo')) {
                $hotel->getMedia('logos')->first()->delete();
                $image = $request->file('logo');
                $hotel->addMedia($image)->toMediaCollection();
                $hotel->getMedia();
            }

            $newLangsIds = Language::whereIn('code', $newLangs)->pluck('id')->toArray();

            foreach ($oldLangs as $oldLangId) {
                if (!in_array($oldLangId, $newLangsIds)) {
                    HotelLanguage::where('hotel_id', $hotel->id)
                        ->where('lang_id', $oldLangId)
                        ->delete();
                }
            }

            foreach ($newLangsIds as $newLangId) {
                if (!in_array($newLangId, $oldLangs)) {
                    HotelLanguage::create([
                        'hotel_id' => $hotel->id,
                        'lang_id' => $newLangId
                    ]);
                }
            }


            return response()->json(['hotel' => $hotel], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/hotels/{id}",
     *     tags={"Hotel"},
     *     summary="Deletes a hotel",
     *     description="Deletes a hotel",
     *     operationId="deleteHotel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of hotel to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hotel deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hotel not found"
     *     )
     * )
     */
    public
    function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', Hotel::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->delete();
            return response()->json(["data" => $hotel]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
