<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

use App\Policies\PostPolicy;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware(['scopes:read-post'], only: ['index', 'show']),
            new Middleware(['scopes:create-post', 'role:admin'], only: ['store']),
            new Middleware(['scopes:update-post', 'role:admin'], only: ['update']),
            new Middleware(['scopes:delete-post', 'role:admin'], only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
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
        //$this->authorize('author', $post);
        
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
            if (Storage::exists( $post->image[0]->url)) {
                Storage::delete($post->image[0]->url);
            }
            $image_url = $request->image->storeAs('posts/', $request->user_id . '_' . $request->slug . '.' . $request->image->extension());

            $image_url = 'posts/' . substr($image_url, 7);

            $post->image()->update(['url' => $image_url]);
        } 

        $post->update($request->all());

        return PostResource::make($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //$this->authorize('author', $post);

        $post->delete();

        return PostResource::make($post);
    }
}
