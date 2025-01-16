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
            new Middleware('auth:api', except: ['index', 'show']),
            new Middleware(['scopes:create-category', \Spatie\Permission\Middleware\PermissionMiddleware::using('create categories', 'api')], only: ['store']),
            new Middleware(['scopes:update-category', \Spatie\Permission\Middleware\PermissionMiddleware::using('edit categories', 'api')], only: ['update']),
            new Middleware(['scopes:delete-category', \Spatie\Permission\Middleware\PermissionMiddleware::using('delete categories', 'api')], only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
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
