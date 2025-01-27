<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TagController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware(['scopes:read-tag'], only: ['index', 'show']),
            new Middleware(['scopes:create-tag', \Spatie\Permission\Middleware\PermissionMiddleware::using('create tags', 'api')], only:['store']),
            new Middleware(['scopes:update-tag', \Spatie\Permission\Middleware\PermissionMiddleware::using('edit tags', 'api')], only:['update']),
            new Middleware(['scopes:delete-tag', \Spatie\Permission\Middleware\PermissionMiddleware::using('delete tags', 'api')], only:['destroy']),
        ];
    }

    /**
     * Listar todas las Etiquetas
     * @OA\Get (
     *     path="/v1/tags",
     *     tags={"Etiquetas"},
     *  security={
     *  {"access_token": {}},
     *   },
     *      @OA\Parameter(
     *          name="included",
     *          in="query",
     *          description="Incluir una o muchas relaciones entre tablas. Ejemplo: posts o posts.users,posts.tags,posts.image",
     *          required=false,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="filter[name]",
     *          in="query",
     *          description="Filtra los resultados según el nombre de la columna dentro de los corchetes []. El valor 'name' puede ser sutituido por el nombre de cualquier columna de la tabla Posts. Ejemplo: filter[name]=nombreDeCategoria",
     *          required=false,
     *          @OA\Schema(
     *               type="mixed types"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="Ordena los resultados según el valor del parametro. Ejemplo: 'id' para ordernar por el id de forma ascendente o '-id' para ordernar por el id de forma descendente",
     *          required=false,
     *          @OA\Schema(
     *               type="mixed types"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="perPage",
     *          in="query",
     *          description="Paginar los resultados. Ejemplo: '2' para paginar de 2 en 2",
     *          required=false,
     *          @OA\Schema(
     *               type="number"
     *          )
     *      ),
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
     *                         example="Etiqueta de prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="etiqueta-de-prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="color",
     *                         type="string",
     *                         example="red"
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
        $tags = Tag::include()
                        ->filter()
                        ->sort()
                        ->getOrPaginate();

        return TagResource::collection($tags);
    }

    /**
     * Registrar una Etiqueta
     * @OA\Post (
     *     path="/v1/tags",
     *     tags={"Etiquetas"},
     * security={
     *  {"passport": {"create-tag"}},
     *   },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="slug",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="color",
     *                         type="string"
     *                     ),
     *            ),
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Etiqueta de prueba"),
     *              @OA\Property(property="slug", type="string", example="etiqueta-de-prueba"),
     *              @OA\Property(property="color", type="string", example="red"),
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
        if (preg_match('/^[a-z0-9-]+$/', $request->slug)) {
            $request->validate([
                'name' => 'required|max:250',
                'slug' =>'required|unique:tags'
             ]);
    
            $tag = Tag::create($request->all());
    
            return TagResource::make($tag);
        } else {
            return response()->json(['errors' => "Formato de slug no valido.",], 422);
        }
    }

    /**
     * Mostrar la información de una Etiqueta
     * @OA\Get (
     *     path="/v1/tags/{id}",
     *     tags={"Etiquetas"},
     * security={
     *  {"access_token": {}},
     *   },
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *          name="included",
     *          in="query",
     *          description="Incluir una o muchas relaciones entre tablas. Ejemplo: posts o posts.users,posts.tags,posts.image",
     *          required=false,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *        @OA\JsonContent(
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
     *                         example="Etiqueta de prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="etiqueta-de-prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="color",
     *                         type="string",
     *                         example="red"
     *                     ),
     *                 )
     *             )
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
    public function show(int $id)
    {
        $tag = Tag::include()->findOrFail($id);

        return TagResource::make($tag);
    }

    /**
     * Actualizar una Etiqueta
     * @OA\Put (
     *     path="/v1/tags/{id}",
     *     tags={"Etiquetas"},
     * security={
     *  {"passport": {"update-tag"}},
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
     *                 
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="slug",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="color",
     *                          type="string"
     *                      ),
     *            ),
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Etiqueta de prueba"),
     *              @OA\Property(property="slug", type="string", example="etiqueta-de-prueba"),
     *              @OA\Property(property="color", type="string", example="red")
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
    public function update(Request $request, tag $tag)
    {
        $request->validate([
            'name' => 'required|max:250',
            'slug' => 'required|max:250|unique:tags,slug,' . $tag->id, //esto ultimo para que compare todos los slug menos al del registro que queremos actualizar
            'color' => 'required'
        ]);

        $tag->update($request->all());

        return TagResource::make($tag);
    }

    /**
     * Eliminar la información de una Etiqueta
     * @OA\Delete (
     *     path="/v1/tags/{id}",
     *     tags={"Etiquetas"},
     * security={
     *  {"passport": {"delete-tag"}},
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
    public function destroy(tag $tag)
    {
        $tag->delete();

        return TagResource::make($tag);
    }
}
