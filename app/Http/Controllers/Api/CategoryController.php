<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

use App\Http\Resources\CategoryResource;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware(['scopes:read-category'], only: ['index', 'show']),
            new Middleware(['scopes:create-category', \Spatie\Permission\Middleware\PermissionMiddleware::using('create categories', 'api')], only: ['store']),
            new Middleware(['scopes:update-category', \Spatie\Permission\Middleware\PermissionMiddleware::using('edit categories', 'api')], only: ['update']),
            new Middleware(['scopes:delete-category', \Spatie\Permission\Middleware\PermissionMiddleware::using('delete categories', 'api')], only: ['destroy']),
        ];
    }

    /**
     * Listar todas las categorias
     * @OA\Get (
     *     path="/v1/categories",
     *     tags={"Categorias"},
     *  security={
     *  {"access_token": {}},
     *   },
     *      @OA\Parameter(
     *      name="included",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Response(
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
     *                         example="Categoria de prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="slug-de-prueba"
     *                     ),
     *                     @OA\Property(
     *                         type="array",
     *                         property="posts",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="number",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                                 example="Post de prueba"
     *                             ),
     *                             @OA\Property(
     *                                 property="slug",
     *                                 type="string",
     *                                 example="slug-de-prueba"
     *                             ),
     *                             @OA\Property(
     *                                 property="stract",
     *                                 type="string",
     *                                 example="Extracto de prueba"
     *                             ),
     *                             @OA\Property(
     *                                 property="body",
     *                                 type="string",
     *                                 example="Cuerpo del post. Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto dolores expedita aut cum delectus! Culpa, consequatur tenetur! Vitae molestias, nisi, itaque explicabo dicta corrupti rem nemo, a deserunt impedit corporis."
     *                             ),
     *                             @OA\Property(
     *                                 property="status",
     *                                 type="string",
     *                                 example="PUBLICADO"
     *                             ),
     *                             @OA\Property(
     *                                 property="category_id",
     *                                 type="number",
     *                                 example="2"
     *                             ),
     *                             @OA\Property(
     *                                 property="user_id",
     *                                 type="number",
     *                                 example="1"
     *                             )
     *                         )
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
        $categories = Category::include()
                        ->filter()
                        ->sort()
                        ->getOrPaginate();

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'slug' =>'required|unique:categories'
         ]);

        $category = Category::create($request->all());

        return CategoryResource::make($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::include()->findOrFail($id);

        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:250',
            'slug' => 'required|max:250|unique:categories,slug,' . $category->id, //esto ultimo para que compare todos los slug menos al del registro que queremos actualizar
        ]);

        $category->update($request->all());

        return CategoryResource::make($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return CategoryResource::make($category);
    }
}
