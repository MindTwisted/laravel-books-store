<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCart;
use App\Http\Requests\UpdateCart;
use App\Http\Requests\DeleteCart;
use App\Cart;

class CartController extends Controller
{
    /**
     * Get cart of current user
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $cart = $user->cart()->with(['user', 'book'])->get();

        return response()->json([
            'data' => compact('cart')
        ]);
    }

    /**
     * Store to cart
     */
    public function store(StoreCart $request): JsonResponse
    {
        $user = auth()->user();
        $cart = Cart::create([
            'count' => $request->input('count'),
            'book_id' => $request->input('book_id'),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => "Book was successfully added to cart."
        ]);
    }

    /**
     * Update in cart
     */
    public function update(Cart $cart, UpdateCart $request): JsonResponse
    {
        $user = auth()->user();
        $cart = $cart->update([
            'count' => $request->input('count')
        ]);

        return response()->json([
            'message' => "Book was successfully updated in cart."
        ]);
    }

    /**
     * Delete from cart
     */
    public function destroy(Cart $cart, DeleteCart $request): JsonResponse
    {
        $cart->delete();
        
        return response()->json([
            'message' => "Book was successfully deleted from cart."
        ]);
    }
}
