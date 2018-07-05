<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThreadResource;
use App\Notifications\LikedMyThread;
use App\Notifications\SubscribedMyThread;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $threads = Thread::published()->latest()->filter($request->all())->paginate($request->get('per_page', 20));

        return ThreadResource::collection($threads);
    }

    public function report(Request $request, Thread $thread)
    {
        $request->validate([
            'remark' => 'required'
        ]);

        $thread->report()->create([
            'user_id' => auth()->id(),
            'remark' => $request->get('remark'),
        ]);

        return response()->json([]);
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
            'title' => 'required|min:6|user_unique_content:threads,title',
            'type' => 'in:markdown,html',
            'content.body' => 'required_if:type,html',
            'content.markdown' => 'required_if:type,markdown',
            'ticket' => 'required|ticket:publish',
            'is_draft' => 'boolean',
        ]);

        return new ThreadResource(Thread::create($request->all()));
    }

    /**
     * @param \App\Thread $thread
     *
     * @return \App\Http\Resources\ThreadResource
     */
    public function show(Thread $thread)
    {
        $thread->loadMissing('content');

        $thread->update(['cache->views_count' => $thread->cache['views_count'] + 1]);

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
        $this->authorize('update', $thread);

        $this->validate($request, [
            'title' => 'required|min:6|user_unique_content:threads,title,'.$thread->id,
            'type' => 'in:markdown,html',
            'content.body' => 'required_if:type,html',
            'content.markdown' => 'required_if:type,markdown',
            'ticket' => 'required|ticket:publish',
            'is_draft' => 'boolean',
        ]);

        $thread->update($request->all());

        return new ThreadResource($thread);
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
