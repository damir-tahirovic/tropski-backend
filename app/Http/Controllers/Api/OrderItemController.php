<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderItemController extends Controller
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
        //        try {
//            $this->authorize('view', OrderItem::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        try {
//            $this->authorize('create', OrderItem::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //        try {
//            $this->authorize('viewAny', OrderItem::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //        try {
//            $this->authorize('update', OrderItem::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', OrderItem::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
    }
}
