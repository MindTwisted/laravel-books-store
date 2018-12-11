<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrder;
use App\Http\Requests\UpdateOrder;
use App\OrderDetail;
use App\Order;

class OrderController extends Controller
{
    /**
     * Get all orders
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        if ('admin' === $user->role)
        {
            $orders = Order::with(['orderDetails', 'user', 'paymentType'])->get();

            return response()->json([
                'data' => compact('orders')
            ]);
        }

        $orders = $user->orders()->with(['orderDetails', 'user', 'paymentType'])->get();

        return response()->json([
            'data' => compact('orders')
        ]);
    }

    /**
     * Create new order
     */
    public function store(StoreOrder $request): JsonResponse
    {
        $totalDiscount = 0;
        $totalPrice = 0;

        $user = auth()->user();
        $cart = $user->cart()->with('book')->get();

        $orderDetailsData = [];

        $cart->each(function($item, $key) use (
            &$totalDiscount, 
            &$totalPrice, 
            &$orderDetailsData,
            $user
        ) {
            $booksCount = +$item->count;
            $bookFullPrice = +$item->book->price;
            
            $totalDiscountPercent = +$user->discount + +$item->book->discount;
            $totalDiscountPercent = $totalDiscountPercent > 50 ? 50 : +$totalDiscountPercent;
            
            $bookDiscount = round($bookFullPrice * ($totalDiscountPercent / 100), 2);
            $bookPriceWithDiscount = round($bookFullPrice - $bookDiscount, 2);
            
            $totalDiscount += $bookDiscount * $booksCount;
            $totalPrice += $bookPriceWithDiscount * $booksCount;

            $orderDetailsData[] = [
                'book_title' => $item->book->title,
                'book_count' => $booksCount,
                'book_price' => $bookPriceWithDiscount,
                'book_discount' => $bookDiscount,
                'book_id' => $item->book->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        });

        DB::transaction(function() use (
            $orderDetailsData, 
            $totalDiscount, 
            $totalPrice, 
            $request,
            $user) 
        {
            $order = Order::create([
                'total_discount' => $totalDiscount,
                'total_price' => $totalPrice,
                'user_id' => $user->id,
                'payment_type_id' => $request->input('payment_type')
            ]);

            foreach ($orderDetailsData as &$data)
            {
                $data['order_id'] = $order->id;
            }

            OrderDetail::insert($orderDetailsData);

            $user->cart()->delete();
        });

        return response()->json([
            'message' => "Order was successfully added."
        ]);
    }

    /**
     * Update order
     */
    public function update(Order $order, UpdateOrder $request): JsonResponse
    {
        $order->update([
            'status' => $request->input('status')
        ]);

        return response()->json([
            'message' => "Order was successfully updated."
        ]);
    }

    /**
     * Delete order
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json([
            'message' => "Order was successfully deleted."
        ]);
    }
}
