<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelLanguage;
use Exception;
use Illuminate\Http\Request;


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
        $hotels = Hotel::with('mainCategories')->get();
        $hotels->load('media');
        return response()->json($hotels);
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
        $hotels = Hotel::with('languages')->get();
        $hotels->load('media');
        return response()->json($hotels);
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
        try {

            $langs = $request->input('languages');

            if (!isset($langs) || empty($langs)) {
                return response()->json(['languages' => 'At least one language is required'], 421);
            }

            $validated = $request->validate([
                "name" => "required|max:255",
                "description" => "nullable",
                'colors' => 'required',
                "image" => "required",
                "banner_image" => "required",
                "logo" => "required"
            ]);


            $primaryColor = $request->input('colors.primary_color');
            $primaryColorLight = $request->input('colors.primary_color_light');
            $primaryColorDark = $request->input('colors.primary_color_dark');
            $secondaryColor = $request->input('colors.secondary_color');
            $secondaryColorLight = $request->input('colors.secondary_color_light');
            $secondaryColorDark = $request->input('colors.secondary_color_dark');

            $hotel = Hotel::create([
                "name" => $request->input('name'),
                "description" => $request->input('description'),
                "primary_color" => $primaryColor,
                "primary_color_light" => $primaryColorLight,
                "primary_color_dark" => $primaryColorDark,
                "secondary_color" => $secondaryColor,
                "secondary_color_light" => $secondaryColorLight,
                "secondary_color_dark" => $secondaryColorDark,
//                "banner_text" => $request->input('banner_text'),
                "his_id" => 1
            ]);


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $hotel->addMedia($image)->toMediaCollection();
                $hotel->getMedia();
            }
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $hotel->addMedia($image)->toMediaCollection();
                $hotel->getMedia();
            }
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $hotel->addMedia($image)->toMediaCollection();
                $hotel->getMedia();
            }

            foreach ($langs as $lang) {
                HotelLanguage::create([
                    'hotel_id' => $hotel->id,
                    'lang_id' => $lang['id']
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
        try {
            $hotel = Hotel::findOrFail($id);
            $validated = $request->validate([
                "name" => "required|max:255",
                "description" => "nullable",
            ]);
            $hotel->update($validated);
            return response()->json(["data" => $hotel]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
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
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->delete();
            return response()->json(["data" => $hotel]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
