<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Notifications\CommentMyThread;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'active_user'])->except(['index', 'show']);
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
        $this->validate($request, [
            'user_id' => 'require_without:commentable_id',
            'commentable_id' => 'required_without:user_id|poly_exists:commentable_type',
        ]);

        if ($request->has('user_id')) {
            $builder = Comment::whereUserId($request->get('user_id'));
        } else {
            $model = $request->get('commentable_type');
            $builder = (new $model())->find($request->get('commentable_id'))->comments();
        }

        $comments = $builder->oldest()->valid()->filter($request->all())->paginate($request->get('per_page', 20));

        return CommentResource::collection($comments);
    }

    public function upVote(Comment $comment)
    {
        auth()->user()->upvote($comment);

        return response()->json([]);
    }

    public function downVote(Comment $comment)
    {
        auth()->user()->downvote($comment);

        return response()->json([]);
    }

    public function cancelVote(Comment $comment)
    {
        auth()->user()->cancelVote($comment);

        return response()->json([]);
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

        if (!Comment::isCommentable($request->get('commentable_type'))) {
            abort(403, 'Invalid request.');
        }

        $comment = Comment::create($request->all());

        // XXX: 不科学
        $comment->commentable->user->notify(new CommentMyThread($comment, auth()->user()));

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

        $comment->update($request->all());

        return new CommentResource($comment);
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
