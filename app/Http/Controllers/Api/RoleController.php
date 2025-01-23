<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create roles', 'api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit roles', 'api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete roles', 'api'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create($request->all());

        $permissions = $request->permissions;
        $addPermissions = [];

        foreach ($permissions as $permission) {
            switch ($permission) {
                case 'admin.categories.create':
                    array_push($addPermissions, 4);
                    break;
                
                case 'admin.categories.edit':
                    array_push($addPermissions, 5);
                    break;

                case 'admin.categories.destroy':
                    array_push($addPermissions, 6);
                    break;

                case 'admin.tags.create':
                    array_push($addPermissions, 7);
                    break;

                case 'admin.tags.edit':
                    array_push($addPermissions, 8);
                    break;

                case 'admin.tags.destroy':
                    array_push($addPermissions, 9);
                    break;

                case 'admin.posts.create':
                    array_push($addPermissions, 1);
                    break;

                case 'admin.posts.edit':
                    array_push($addPermissions, 2);
                    break;

                case 'admin.posts.destroy':
                    array_push($addPermissions, 3);
                    break;

                case 'admin.users.edit':
                    array_push($addPermissions, 10);
                    break;

                default:
                    break;
            }
        }

        if (!empty($addPermissions)) {
            $role->permissions()->sync($addPermissions);    
        } 

        return RoleResource::make($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return RoleResource::make($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $role)
    {
        $role = Role::where('name', $role)->first();

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->all());

        $permissions = $request->permissions;
        $addPermissions = [];

        foreach ($permissions as $permission) {
            switch ($permission) {
                case 'admin.categories.create':
                    array_push($addPermissions, 4);
                    break;
                
                case 'admin.categories.edit':
                    array_push($addPermissions, 5);
                    break;

                case 'admin.categories.destroy':
                    array_push($addPermissions, 6);
                    break;

                case 'admin.tags.create':
                    array_push($addPermissions, 7);
                    break;

                case 'admin.tags.edit':
                    array_push($addPermissions, 8);
                    break;

                case 'admin.tags.destroy':
                    array_push($addPermissions, 9);
                    break;

                case 'admin.posts.create':
                    array_push($addPermissions, 1);
                    break;

                case 'admin.posts.edit':
                    array_push($addPermissions, 2);
                    break;

                case 'admin.posts.destroy':
                    array_push($addPermissions, 3);
                    break;

                case 'admin.users.edit':
                    array_push($addPermissions, 10);
                    break;

                default:
                    break;
            }
        }

        if (!empty($addPermissions)) {
            $role->permissions()->sync($addPermissions);    
        } 

        return RoleResource::make($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role)
    {
        $role = Role::where('name', $role)->first();

        $role->delete();

        return RoleResource::make($role);
    }
}
