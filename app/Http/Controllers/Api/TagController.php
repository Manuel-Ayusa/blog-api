<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'slug' =>'required|unique:tags'
         ]);

        $tag = Tag::create($request->all());

        return TagResource::make($tag);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $tag = Tag::include()->findOrFail($id);

        return TagResource::make($tag);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(tag $tag)
    {
        $tag->delete();

        return TagResource::make($tag);
    }
}
