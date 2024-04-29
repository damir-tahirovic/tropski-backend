<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HotelUserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotel-users",
     *     tags={"HotelUser"},
     *     summary="Finds all hotel users",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="hotel-users.index",
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
        $hotelUser = HotelUser::all();
        return response()->json(['hotelUsers' => $hotelUser]);
    }

    /**
     * @OA\Post(
     *     path="/api/hotel-users",
     *     tags={"HotelUser"},
     *     summary="Create a new hotel user",
     *     operationId="hotel-users.store",
     *     @OA\RequestBody(
     *         description="Hotel user data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/HotelUser")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Hotel user created successfully"
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
                'user_id' => 'required',
                'hotel_id' => 'required'
            ]);
            $user = User::findOrFail($request->input('user_id'));
            $hotelUser = Hotel::findOrFail($request->input('hotel_id'));
            HotelUser::create($request->all());
            return response()->json('Hotel user created successfully');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/hotel-users/{id}",
     *     tags={"HotelUser"},
     *     summary="Find hotel user by ID",
     *     description="Returns a single hotel user",
     *     operationId="hotel-users.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of hotel user to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid hotel user ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hotel user not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $hotelUser = HotelUser::findOrFail($id);
            return response()->json(['hotelUser' => $hotelUser]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/hotel-users/{id}",
     *     tags={"HotelUser"},
     *     summary="Update an existing hotel user",
     *     description="",
     *     operationId="hotel-users.update",
     *     @OA\RequestBody(
     *         description="Hotel user object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/HotelUser")
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
     *         description="Hotel user not found"
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
            $validated = $request->validate([
                'user_id' => 'required',
                'hotel_id' => 'required'
            ]);
            $user = User::findOrFail($request->input('user_id'));
            $hotelUser = Hotel::findOrFail($request->input('hotel_id'));
            HotelUser::update($request->all());
            return response()->json('Hotel user updated successfully');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/hotel-users/{id}",
     *     tags={"HotelUser"},
     *     summary="Deletes a hotel user",
     *     description="",
     *     operationId="hotel-users.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Hotel user id to delete",
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
     *         description="Hotel user not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $hotelUser = HotelUser::findOrFail($id);
            $hotelUser->delete();
            return response()->json('Hotel user deleted successfully');
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
