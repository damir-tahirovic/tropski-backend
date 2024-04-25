<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\OrderPlace;
use App\Models\Hotel;
use App\Models\MainCategory;
use Illuminate\Http\Response;

class OrderPlaceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/order-places",
     *     tags={"OrderPlace"},
     *     summary="Finds all order places",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="order-places.index",
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
        $orderPlaces = OrderPlace::all();
        return response()->json(['orderPlaces' => $orderPlaces]);
    }

    /**
     * @OA\Post(
     *     path="/api/order-places",
     *     tags={"OrderPlace"},
     *     summary="Create a new order place",
     *     operationId="order-places.store",
     *     @OA\RequestBody(
     *         description="OrderPlace object that needs to be added",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderPlace")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OrderPlace created"
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
                'hotel_id' => 'required',
                'main_cat_id' => 'required',
                'name' => 'required|max:255',
                'code' => 'required'
            ]);
            $main_cat_id = MainCategory::findOrFail($validated['main_cat_id']);
            $hotel_id = Hotel::findOrFail($validated['hotel_id']);
            $orderPlace = OrderPlace::create($request->all());
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/order-places/{id}",
     *     tags={"OrderPlace"},
     *     summary="Find order place by ID",
     *     description="Returns a single order place",
     *     operationId="order-places.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of order place to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OrderPlace")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order place not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $orderPlace = OrderPlace::findOrFail($id);
            return response()->json(['orderPlace' => $orderPlace]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/order-places/{id}",
     *     tags={"OrderPlace"},
     *     summary="Updates an order place",
     *     operationId="order-places.update",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="OrderPlace id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="OrderPlace object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderPlace")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OrderPlace updated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'hotel_id' => 'required',
                'main_cat_id' => 'required',
                'name' => 'required|max:255',
                'code' => 'required'
            ]);
            $main_cat_id = MainCategory::findOrFail($validated['main_cat_id']);
            $hotel_id = Hotel::findOrFail($validated['hotel_id']);
            $orderPlace = OrderPlace::findOrFail($id);
            $orderPlace->update($request->all());
            return response()->json(['orderPlace' => $orderPlace]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/order-places/{id}",
     *     tags={"OrderPlace"},
     *     summary="Deletes an order place",
     *     operationId="order-places.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="OrderPlace id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OrderPlace deleted"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid OrderPlace id"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $orderPlace = findOrFail($id);
            $orderPlace->delete();
            return response()->json(['orderPlace' => $orderPlace]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
