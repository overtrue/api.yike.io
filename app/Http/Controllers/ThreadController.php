<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThreadResource;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $threads = Thread::latest()->filter($request->all())->paginate($request->get('per_page', 20));

        return ThreadResource::collection($threads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\ThreadResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Thread::class);

        $this->validate($request, [
        ]);

        return new ThreadResource(Thread::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Thread $thread
     *
     * @return \App\Http\Resources\ThreadResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Thread $thread)
    {
        $this->authorize('view', $thread);

        return new ThreadResource($thread);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Thread              $thread
     *
     * @return \App\Http\Resources\ThreadResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', Thread::class);

        $this->validate($request, [
            // validation rules...
        ]);

        $supplier->update($request->all());

        return new ThreadResource(Thread::create($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Thread $thread
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return $this->withNoContent();
    }
}
