<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseController;
use App\Services\AuthService;

class AuthController extends BaseController
{
    protected $serviceClass = AuthService::class;

    protected $rules = [
        'always' => [
            'email' => 'required|email',
            'password' => 'required|string',
        ],
    ];
    

    /**
     * Login and token generation
     */
    public function login(Request $request): JsonResponse 
    {
        $this->validateRequest($request, $this->rules['always']);
        $token = $this->serviceClass->createToken($request->email, $request->password);

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token
        ]);
    }

    /**
     * Logout and token revocation
     */
    public function logout(Request $request)
    {
        $this->serviceClass->deleteTokens($request->user()->id);

        return response()->json(['message' => 'Logout successful.']);
    }
}
