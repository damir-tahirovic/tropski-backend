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

//    /**
//     * @OA\Get(
//     *     path="/api/order-places",
//     *     tags={"OrderPlace"},
//     *     summary="Get all order places",
//     *     operationId="order-places.index",
//     *     @OA\Response(
//     *         response=200,
//     *         description="successful operation",
//     *         @OA\JsonContent(
//     *             type="array",
//     *             @OA\Items(ref="#/components/schemas/OrderPlace")
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=400,
//     *         description="Invalid request"
//     *     )
//     * )
//     */
    public function index()
    {
        //        try {
//            $this->authorize('view', OrderPlace::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $orderPlaces = OrderPlace::all();
            return response()->json(['orderPlaces' => $orderPlaces]);

        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

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
        //        try {
//            $this->authorize('create', OrderPlace::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $validated = $request->validate([
                'hotel_id' => 'required',
                'main_cat_id' => 'required',
                'name' => 'required|max:255',
                'code' => 'required'
            ]);
            $hotel_id = Hotel::findOrFail($validated['hotel_id']);
            $main_cat_id = MainCategory::findOrFail($validated['main_cat_id']);
            $orderPlace = OrderPlace::create($request->all());
            return response()->json(['orderPlace' => $orderPlace], 201);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 400);
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
        //        try {
//            $this->authorize('viewAny', OrderPlace::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
        //        try {
//            $this->authorize('update', OrderPlace::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
        //        try {
//            $this->authorize('forceDelete', OrderPlace::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $orderPlace = findOrFail($id);
            $orderPlace->delete();
            return response()->json(['orderPlace' => $orderPlace]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
