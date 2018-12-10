<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UpdateUser;
use App\Http\Requests\UpdateCurrentUser;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json([
            'data' => compact('users')
        ]);
    }

    /**
     * Get user by id
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => compact('user')
        ]);
    }

    // TODO: add showOrders method

    /**
     * Update user
     */
    public function update(User $user, UpdateUser $request): JsonResponse
    {
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'discount' => $request->input('discount')
        ]);

        return response()->json([
            'message' => "User was successfully updated.",
            'data' => compact('user')
        ]);
    }

    /**
     * Update current user
     */
    public function updateCurrent(UpdateCurrentUser $request): JsonResponse
    {
        $user = auth()->user();
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'message' => "User was successfully updated.",
            'data' => compact('user')
        ]);
    }
}
