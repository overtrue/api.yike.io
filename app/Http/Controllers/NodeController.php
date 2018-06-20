<?php

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Http\Resources\ThreadResource;
use App\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'threads']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $builder = Node::with('children')->root();
        } else {
            $builder = Node::leaf();
        }

        $nodes = $builder->latest()
                    ->filter($request->all())
                    ->paginate($request->get('per_page', 20));

        return NodeResource::collection($nodes);
    }

    public function threads(Request $request, Node $node)
    {
        $threads = $node->threads()
                        ->published()
                        ->latest()
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 20));

        return ThreadResource::collection($threads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\NodeResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Node::class);

        $this->validate($request, [
        ]);

        return new NodeResource(Node::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Node $node
     *
     * @return \App\Http\Resources\NodeResource
     */
    public function show(Node $node)
    {
        return new NodeResource($node);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Node                $node
     *
     * @return \App\Http\Resources\NodeResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Node $node)
    {
        $this->authorize('update', Node::class);

        $this->validate($request, [
            // validation rules...
        ]);

        $node->update($request->all());

        return new NodeResource(Node::create($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Node $node
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Node $node)
    {
        $this->authorize('delete', $node);

        $node->delete();

        return $this->withNoContent();
    }
}
