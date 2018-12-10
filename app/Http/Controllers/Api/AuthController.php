<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;

class AuthController extends Controller
{
    /**
     * Authenticate user
     */
    public function login(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $credentials = compact('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) 
            {
                return response()->json([
                    'message' => 'The credentials you supplied were not correct.'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'An error occurred, please try again later.'
            ], 500);
        }

        return response()->json([
            'message' => 'User was successfully logged in.',
            'data' => compact('token')
        ]);
    }

    /**
     * Register new user
     */
    public function register(RegisterUser $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User was successfully registered.',
            'data' => compact('user','token')
        ], 201);
    }

    /**
     * Get information about authenticated user
     */
    public function current(): JsonResponse
    {
        try {

            if (!($user = JWTAuth::parseToken()->authenticate())) {
                    return response()->json([
                        'message' => 'User not found.'
                    ], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json([
                'message' => 'Token expired.'
            ], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json([
                'message' => 'Token invalid.'
            ], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json([
                'message' => 'Token absent.'
            ], $e->getStatusCode());

        }

        return response()->json([
            'data' => compact('user')
        ]);
    }
}
