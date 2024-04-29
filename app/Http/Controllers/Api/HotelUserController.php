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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
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
