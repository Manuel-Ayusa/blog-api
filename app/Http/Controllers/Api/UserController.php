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

    /**
     * Solo accesible para usuarios con Roles especificos.
     * @OA\Put (
     *     path="/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Asignar uno o mas roles a un Usuario",
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
     *                      property="roles",
     *                      type="array",
     *                      @OA\Items()
     *                 ),
     *            ),
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Errors",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The roles field is required."),
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
    public function update(Request $request, User $user)
    {   
        $user->roles()->sync($request->roles);

        return response(200);
    }
}
