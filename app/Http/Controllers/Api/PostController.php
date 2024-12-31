<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Policies\PostPolicy;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
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
            'slug' => 'required|unique:posts',
            'category_id' => 'required',
            'status' => 'required|in:1,2'
        ]);
        
        if ($request->status == 2) {
            $request->validate([
                'tags' => 'required',
                'stract' => 'required',
                'body' => 'required',
                'image' => 'required|image'
            ]);
        }

        $post = Post::create($request->all());

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        if ($request->image) {
            $image_url = $request->image->storeAs('posts/', $request->user_id . '_' . $request->name . '.' . $request->image->extension());

            $image_url = 'posts/' . substr($image_url, 7);

            $post->images()->create(['url' => $image_url]);
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
            'name' => 'required|max:250',
            'slug' => 'required|max:250|unique:posts,slug,' . $post->id,
            'stract' => 'required',
            'body' => 'required',
            'category_id' => 'required|exist:categories,id',
            'user_id' => 'required|exist:user,id'
        ]);

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
