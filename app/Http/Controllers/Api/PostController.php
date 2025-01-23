<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
* @OA\Info(
*             title="API Blog", 
*             version="1.0",
*             description="Listado de los endpoints de API Blog"
* )
*
* @OA\Server(url="http://api.codersfree.test")
*/

class PostController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;
    
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware(['scopes:read-post'], only: ['index', 'show']),
            new Middleware(['scopes:create-post', \Spatie\Permission\Middleware\PermissionMiddleware::using('create posts', 'api')], only:['store']),
            new Middleware(['scopes:update-post', \Spatie\Permission\Middleware\PermissionMiddleware::using('edit posts', 'api')], only:['update']),
            new Middleware(['scopes:delete-post', \Spatie\Permission\Middleware\PermissionMiddleware::using('delete posts', 'api')], only:['destroy']),
        ];
    }
    /**
     * Listar todos los Posts
     * @OA\Get (
     *     path="/v1/posts",
     *     tags={"Posts"},
     *  security={
     *  {"access_token": {}},
     *   },
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
     *                         example="Post de prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="slug-de-prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="stract",
     *                         type="string",
     *                         example="Extracto de prueba"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="string",
     *                         example="Cuerpo del post. Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto dolores expedita aut cum delectus! Culpa, consequatur tenetur! Vitae molestias, nisi, itaque explicabo dicta corrupti rem nemo, a deserunt impedit corporis."
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="PUBLICADO"
     *                     ),
     *                     @OA\Property(
     *                         property="category_id",
     *                         type="number",
     *                         example="2"
     *                     ),
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="number",
     *                         example="1"
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
        $posts = Post::include()
                        ->filter()
                        ->sort()
                        ->getOrPaginate();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:1,2',
            'user_id' => 'required|exists:users,id',
            'image' => 'image'
        ]);
        
        if ($request->status == 2) {
            $request->validate([
                'tags' => 'required',
                'stract' => 'required',
                'body' => 'required',
            ]);
        }

        $post = Post::create($request->all());

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        if ($request->image) {
            $image_url = $request->image->storeAs('posts/', $request->user_id . '_' . $request->slug . '.' . $request->image->extension());

            $image_url = 'posts/' . substr($image_url, 7);

            $post->image()->create(['url' => $image_url]);
        }

        return PostResource::make($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $post = Post::include()->findOrFail($id);

        return PostResource::make($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {   
        $this->authorize('author', $post);
        
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:1,2',
            'user_id' => 'required|exists:users,id',
            'image' => 'image'
        ]);
        
        if ($request->status == 2) {
            $request->validate([
                'tags' => 'required',
                'stract' => 'required',
                'body' => 'required',
            ]);
        }

        $post->tags()->detach();
        $post->tags()->attach($request->tags);

        if ($request->image) {

            if (!empty($post->image[0])) {
                if (Storage::exists( $post->image[0]->url)) {
                    Storage::delete($post->image[0]->url);
                }
            }
            
            $image_url = $request->image->storeAs('posts/', $request->user_id . '_' . $request->slug . '.' . $request->image->extension());

            $image_url = 'posts/' . substr($image_url, 7);

            if (empty($post->image[0])) {
                $post->image()->create(['url' => $image_url]);
            } else {
                $post->image()->update(['url' => $image_url]);    
            }
            
        } 

        $post->update($request->all());

        return PostResource::make($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('author', $post);

        $post->delete();

        return PostResource::make($post);
    }
}
