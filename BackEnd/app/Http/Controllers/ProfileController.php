<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile information.
     *
     * @return \App\Http\Resources\UserResource
     */
    public function show()
    {
        $user = Auth::user()->load('roles');
        return new UserResource($user);
    }
} 