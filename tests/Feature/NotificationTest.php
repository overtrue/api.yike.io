<?php

namespace Tests\Feature;

use App\Notifications\Welcome;
use App\User;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testUserCanGetNotifications()
    {
        $user = \factory(User::class)->create();

        $user->notify(new Welcome());

        // not logged
        $this->getJson('api/notifications')->assertStatus(401);

        // logged
        $notifications = json_decode($this->actingAs($user, 'api')->getJson('api/notifications')
            ->assertStatus(200)
            ->getContent(), true);

        $this->assertCount(1, $notifications);
        $this->assertArraySubset(['data' => ['user_id' => $user->id]], $notifications[0]);
    }

    public function testUserCanMarkNotificationAsRead()
    {
        $user = \factory(User::class)->create();

        $user->notify(new Welcome());

        $notifications = json_decode($this->actingAs($user, 'api')->getJson('api/notifications')
            ->assertStatus(200)
            ->getContent(), true);
        $first = $notifications[0];

        $this->patchJson('api/notifications/'.$first['id'])->assertStatus(200);
        $notifications = json_decode($this->actingAs($user, 'api')->getJson('api/notifications')
            ->assertStatus(200)
            ->getContent(), true);
        $first = $notifications[0];

        $this->assertNotNull($first['read_at']);
    }

    public function testUserCanMarkAllNotificationAsRead()
    {
        $user = \factory(User::class)->create();

        $user->notify(new Welcome());
        $user->notify(new Welcome());

        $this->actingAs($user, 'api')->postJson('api/notifications/mark-all-as-read')
                ->assertStatus(200);

        $notifications = json_decode($this->actingAs($user, 'api')->getJson('api/notifications')
            ->assertStatus(200)
            ->getContent(), true);

        $this->assertNotNull($notifications[0]['read_at']);
        $this->assertNotNull($notifications[1]['read_at']);
    }
}
