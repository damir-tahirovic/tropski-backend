<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"Role"},
     *     summary="Get all roles",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="roles.index",
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
//            $this->authorize('view', Role::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $roles = Role::all();
            return response()->json(['roles' => $roles], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     tags={"Role"},
     *     summary="Create a new role",
     *     operationId="roles.store",
     *     @OA\RequestBody(
     *         description="Role data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Role")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully"
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
//            $this->authorize('create', Role::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'required'
            ]);
            $role = Role::create($validated);
            return response()->json(['role' => $role], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Find role by ID",
     *     description="Returns a single role",
     *     operationId="roles.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid role ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', Role::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $role = Role::findOrFail($id);
            return response()->json($role, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Update an existing role",
     *     description="",
     *     operationId="roles.update",
     *     @OA\RequestBody(
     *         description="Role object that needs to be updated",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Role")
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
     *         description="Role not found"
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
//            $this->authorize('update', Role::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $role = Role::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable'
            ]);
            $role->update($validated);
            return response()->json(['role' => $role], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Deletes a role",
     *     description="",
     *     operationId="roles.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role id to delete",
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
     *         description="Role not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', Role::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(['role' => $role]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
