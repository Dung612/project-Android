<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'is_verified' => $user->is_verified,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);
    }
} 