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
     * Listar todas los Roles
     * @OA\Get (
     *     path="/v1/roles",
     *     tags={"Roles"},
     *  security={
     *  {"passport": {}},
     *   },
     *      @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Admin"
     *                     ),
     *                     @OA\Property(
     *                         property="guard_name",
     *                         type="string",
     *                         example="web"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     ),
     * )
     */
    public function index()
    {
        $roles = Role::all();

        return RoleResource::collection($roles);
    }

    /**
     * Solo accesible para usuarios con Roles especificos.
     * @OA\Post (
     *     path="/v1/roles",
     *     tags={"Roles"},
     *     summary="Registrar un Rol",
     * security={
     *  {"passport": {""}},
     *   },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="permissions",
     *                          type="array",
     *                          @OA\Items()
     *                      ),
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Admin"
     *                     ),
     *                     @OA\Property(
     *                         property="guard_name",
     *                         type="string",
     *                         example="web"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Errors",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The name field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated."
     *             )
     *          )
     *      ),
     *      @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Forbidden."
     *             )
     *         )
     *     )
     * )
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
     * Mostrar la información de un Rol
     * @OA\Get (
     *     path="/v1/roles/{id}",
     *     tags={"Roles"},
     * security={
     *  {"passport": {}},
     *   },
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Admin"
     *                     ),
     *                     @OA\Property(
     *                         property="guard_name",
     *                         type="string",
     *                         example="web"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      )
     * )
     */
    public function show(Role $role)
    {
        return RoleResource::make($role);
    }

    /**
     * Solo accesible para usuarios con Roles especificos.
     * @OA\Put (
     *     path="/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Actualizar la información de un Rol",
     * security={
     *  {"passport": {""}},
     *   },
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="permissions",
     *                          type="array",
     *                          @OA\Items()
     *                      ),
     *            ),
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Admin"
     *                     ),
     *                     @OA\Property(
     *                         property="guard_name",
     *                         type="string",
     *                         example="web"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Errors",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The name field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated."
     *             )
     *          )
     *      ),
     *      @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Forbidden."
     *             )
     *         )
     *     )
     * )
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
     * Solo accesible para usuarios con Roles especificos.
     * @OA\Delete (
     *     path="/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Eliminar la información de un Rol",
     * security={
     *  {"passport": {""}},
     *   },
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="NO CONTENT"
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se pudo realizar correctamente la operación"),
     *          )
     *      )
     * )
     */
    public function destroy(string $role)
    {
        $role = Role::where('name', $role)->first();

        $role->delete();

        return RoleResource::make($role);
    }
}
