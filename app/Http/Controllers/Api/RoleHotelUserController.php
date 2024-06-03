<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelUser;
use App\Models\Role;
use App\Models\RoleHotelUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleHotelUserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/role-hotel-users",
     *     tags={"RoleHotelUser"},
     *     summary="Get all role hotel users",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="role-hotel-users.index",
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
//            $this->authorize('view', RoleHotelUser::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $roleHotelUsers = RoleHotelUser::all();
            return response()->json(['roleHotelUsers' => $roleHotelUsers], 200);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 400);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/role-hotel-users",
     *     tags={"RoleHotelUser"},
     *     summary="Create a new role hotel user",
     *     operationId="role-hotel-users.store",
     *     @OA\RequestBody(
     *         description="Role hotel user data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/RoleHotelUser")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role hotel user created successfully"
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
//            $this->authorize('create', RoleHotelUser::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $validated = $request->validate([
                'role_id' => 'required',
                'hotel_user_id' => 'required'
            ]);
            $role = Role::findOrFail($validated['role_id']);
            $hotelUser = HotelUser::findOrFail($validated['hotel_user_id']);
            $roleHotelUser = RoleHotelUser::create($validated);
            return response()->json(['roleHotelUser' => $roleHotelUser], 201);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/role-hotel-users/{id}",
     *     tags={"RoleHotelUser"},
     *     summary="Find role hotel user by ID",
     *     description="Returns a single role hotel user",
     *     operationId="role-hotel-users.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role hotel user to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid role hotel user ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role hotel user not found"
     *     )
     * )
     */

    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', RoleHotelUser::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $roleHotelUser = RoleHotelUser::findOrFail($id);
            return response()->json(['roleHotelUser' => $roleHotelUser], 200);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/role-hotel-users/{id}",
     *     tags={"RoleHotelUser"},
     *     summary="Update an existing role hotel user",
     *     description="",
     *     operationId="role-hotel-users.update",
     *     @OA\RequestBody(
     *         description="Role hotel user object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/RoleHotelUser")
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
     *         description="Role hotel user not found"
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
//            $this->authorize('update', RoleHotelUser::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $roleHoterUser = RoleHotelUser::findOrFail($id);
            $validated = $request->validate([
                'role_id' => 'required',
                'hotel_user_id' => 'required'
            ]);
            $role = Role::findOrFail($validated['role_id']);
            $hotelUser = HotelUser::findOrFail($validated['hotel_user_id']);
            $roleHotelUser = RoleHotelUser::create($validated);
            return response()->json(['roleHotelUser' => $roleHotelUser], 201);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/role-hotel-users/{id}",
     *     tags={"RoleHotelUser"},
     *     summary="Deletes a role hotel user",
     *     description="",
     *     operationId="role-hotel-users.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role hotel user id to delete",
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
     *         description="Role hotel user not found"
     *     )
     * )
     */
    public
    function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', RoleHotelUser::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $roleHotelUser = RoleHotelUser::findOrFail($id);
            $roleHotelUser->delete();
            return response()->json(['roleHotelUser' => $roleHotelUser], 200);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 400);
        }
    }
}
