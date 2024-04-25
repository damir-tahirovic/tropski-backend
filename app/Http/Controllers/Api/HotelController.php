<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotel"},
     *     summary="Finds all hotels",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="viewAllHotels",
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
        $hotels = Hotel::all();
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|max:255",
            "his_id" => "required"
        ]);
        $hotel = Hotel::create($validated);
        return response()->json(['data' => $hotel], '201');
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
    public function show($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            return response()->json($hotel);
        } catch (\Exception $e) {
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
    public function update(Request $request, $id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            $validated = $request->validate([
                "name" => "required|max:255",
                "his_id" => "required"
            ]);
            $hotel->update($validated);
            return response()->json(["data" => $hotel]);
        } catch (\Exception $e) {
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
    public function destroy($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->delete();
            return response()->json(["data" => $hotel]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
