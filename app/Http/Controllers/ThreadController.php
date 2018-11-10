<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThreadResource;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'active_user'])->except(['index', 'show', 'search']);
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
        $threads = Thread::published()
            ->orderByDesc('pinned_at')
            ->orderByDesc('excellent_at')
            ->orderByDesc('published_at')
            ->filter($request->all())->paginate($request->get('per_page', 20));

        return ThreadResource::collection($threads);
    }

    public function search(Request $request)
    {
        $threads = Thread::search($request->q)->paginate(10);

        return ThreadResource::collection($threads);
    }

    public function report(Request $request, Thread $thread)
    {
        $request->validate([
            'remark' => 'required',
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

        if (!$thread->user->is_valid) {
            \abort(404);
        }

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

        $rules = [
            'title' => 'required|min:6|user_unique_content:threads,title,'.$thread->id,
            'type' => 'in:markdown,html',
            'content.body' => 'required_if:type,html',
            'content.markdown' => 'required_if:type,markdown',
            'is_draft' => 'boolean',
        ];

        if (!$request->user()->is_admin) {
            $rules['ticket'] = 'required|ticket:publish';
        }

        $this->validate($request, $rules, [
            'ticket.required' => '请先完成验证',
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
