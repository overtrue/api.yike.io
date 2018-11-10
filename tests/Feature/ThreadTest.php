<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    public function testCreateThreadWithoutLogin()
    {
        $this->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(401);
    }

    public function testOnlyUserActivatedCanCreateThread()
    {
        $user = \factory(User::class)->create();
        $this->actingAs($user, 'api')->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(403);

        // activated
        $userActivated = \factory(User::class)->states('activated')->create();
        $this->actingAs($userActivated, 'api')->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(201);
    }

    /**
     * Only logged user can post threads.
     */
    public function testLoggedUserCanCreateThread()
    {
        $user = \factory(User::class)->states('activated')->create();

        $this->actingAs($user, 'api')->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(201)
            ->assertJsonStructure(['title', 'user_id', 'content' => ['body']])
            ->assertJsonFragment(['title' => 'Hello world!', 'body' => 'hello every one.']);

        $this->actingAs($user, 'api')->patchJson('api/threads/1', [
            'title' => 'The New Title',
            'body' => 'updated content.',
        ])->assertJsonFragment([
            'title' => 'The New Title',
            'body' => 'updated content.',
        ]);
    }

    public function testUserCannotUpdateOtherUsersThread()
    {
        $user1 = \factory(User::class)->states('activated')->create();
        $user2 = \factory(User::class)->states('activated')->create();

        $this->actingAs($user1, 'api')->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(201);

        $this->actingAs($user2, 'api')->patchJson('api/threads/1', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertForbidden();
    }

    public function testViewThread()
    {
        $user = \factory(User::class)->states('activated')->create();

        $this->actingAs($user, 'api')->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(201)
            ->assertJsonStructure(['title', 'user_id', 'content' => ['body']])
            ->assertJsonFragment(['title' => 'Hello world!', 'body' => 'hello every one.']);

        $this->get('api/threads/1')->assertJsonFragment([
            'title' => 'Hello world!',
            'user_id' => 1,
            'body' => 'hello every one.',
        ]);
    }

    public function testUserCanOnlyDeleteHisThread()
    {
        $user1 = \factory(User::class)->states('activated')->create();
        $user2 = \factory(User::class)->states('activated')->create();

        $this->actingAs($user1, 'api')->postJson('api/threads', ['title' => 'Hello world!', 'body' => 'hello every one.'])
            ->assertStatus(201);

        // another user
        $this->actingAs($user2, 'api')->deleteJson('api/threads/1')->assertForbidden();

        // author
        $this->actingAs($user1, 'api')->deleteJson('api/threads/1')->assertStatus(204);
    }
}
