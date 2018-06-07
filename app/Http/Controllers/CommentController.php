<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Notifications\CommentMyThread;
use App\Thread;
use Illuminate\Http\Request;

class CommentController extends Controller
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
        $this->validate($request, [
            'commentable_id' => 'required|poly_exists:commentable_type',
        ]);

        $comments = Comment::latest()->filter($request->all())->paginate($request->get('per_page', 20));

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\CommentResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $this->validate($request, [
            'commentable_id' => 'required|poly_exists:commentable_type',
            'type' => 'in:markdown,html',
            'content.body' => 'required_if:type,html',
            'content.markdown' => 'required_if:type,markdown',
        ]);

        $comment = Comment::create($request->all());

        $comment->user->notify(new CommentMyThread($comment, auth()->user()));

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Comment $comment
     *
     * @return \App\Http\Resources\CommentResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Comment $comment)
    {
        $this->authorize('view', $comment);

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Comment             $comment
     *
     * @return \App\Http\Resources\CommentResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', Comment::class);

        $this->validate($request, [
            'type' => 'in:markdown,html',
            'content.body' => 'required_if:type,html',
            'content.markdown' => 'required_if:type,markdown',
        ]);

        $supplier->update($request->all());

        return new CommentResource(Comment::create($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return $this->withNoContent();
    }
}
