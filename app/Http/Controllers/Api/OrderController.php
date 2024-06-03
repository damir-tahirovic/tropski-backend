<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //        try {
//            $this->authorize('view', Order::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $orders = Order::all();
            return response()->json(['orders' => $orders]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //        try {
//            $this->authorize('create', Order::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {

            $data = json_decode($request->getContent(), true);

            $validated = $request->validate([
                'hotel_id' => 'required',
                'ordered_from_id' => 'required',
                'user_id' => 'required',
                'order_items' => 'required',
            ]);

            $order = Order::create([
                'hotel_id' => $request->input('hotel_id'),
                'ordered_from_id' => $request->input('ordered_from_id'),
                'order_datetime' => date('Y-m-d H:i:s'),
                'user_id' => $request->input('user_id'),
                'total_price' => 0
            ]);

            foreach ($data['order_items'] as $order_item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'item_type_id' => $order_item['item_type_id'],
                    'quantity' => $order_item['quantity'],
                ]);
                $itemType = ItemType::findOrFail($order_item['item_type_id']);
                $order->total_price += ($order_item['quantity'] * $itemType->price);
                $order->save();
            }
            $order->order_datetime = $order->created_at;
            $order->save();
            return response()->json(['order' => $order], 201);

        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
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
        //        try {
//            $this->authorize('viewAny', Order::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $order = Order::findOrFail($id);
            return response()->json(['order' => $order]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
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
        //        try {
//            $this->authorize('update', Order::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //        try {
//            $this->authorize('forceDelete', Order::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['order' => $order]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
