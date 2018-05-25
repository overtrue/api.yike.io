<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $tags = Tag::latest()->filter($request->all())->paginate($request->get('per_page', 20));

        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\TagResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);

        $this->validate($request, [
        ]);

        return new TagResource(Tag::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Tag $tag
     *
     * @return \App\Http\Resources\TagResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Tag                 $tag
     *
     * @return \App\Http\Resources\TagResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', Tag::class);

        $this->validate($request, [
            // validation rules...
        ]);

        $supplier->update($request->all());

        return new TagResource(Tag::create($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Tag $tag
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return $this->withNoContent();
    }
}
