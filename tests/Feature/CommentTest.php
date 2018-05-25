<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testUserCanCommentThread()
    {
        $user = \factory(User::class)->create();

        $this->actingAs($user, 'api')->postJson('api/threads', [
                'title' => 'Hello world!',
                'body' => 'hello every one.',
            ])
            ->assertStatus(201);

        $this->postJson('api/comments', [
                'commentable_type' => Thread::class,
                'commentable_id' => 1,
                'body' => 'Very Good!',
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'user_id' => 1,
                'commentable_type' => Thread::class,
                'commentable_id' => 1,
                'body' => 'Very Good!',
            ]);
    }

    public function testUserCannotCommentInvalidObject()
    {
        $user = \factory(User::class)->create();

        $this->actingAs($user, 'api')
                ->postJson('api/comments', [
                'commentable_type' => 'App\\ClassNotExists',
                'commentable_id' => 1,
                'body' => 'Very Good!',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['commentable_id']);
    }
}
