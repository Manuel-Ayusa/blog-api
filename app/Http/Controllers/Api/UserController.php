<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{   
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update users', 'api'), only:['update']),
        ];
    }

    public function update(Request $request, User $user)
    {   
        $user->roles()->sync($request->roles);

        return response(200);
    }
}
