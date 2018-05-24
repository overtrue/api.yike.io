<?php

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $nodes = Node::latest()->filter($request->all())->paginate($request->get('per_page', 20));

        return NodeResource::collection($nodes);
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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Node $node)
    {
        $this->authorize('view', $node);

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

        $supplier->update($request->all());

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
