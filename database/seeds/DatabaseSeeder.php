<?php

use App\Node;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::create([
            'name' => '超级管理员',
            'username' => 'admin',
            'email' => 'admin@yike.io',
            'password' => 'yikeadmin',
            'activated_at' => now(),
            'is_admin' => true,
        ]);

        $root = Node::create([
            'title' => '微信开发',
        ]);

        Node::create(['node_id' => $root->id, 'title' => '公众号']);
        Node::create(['node_id' => $root->id, 'title' => '小程序']);
        Node::create(['node_id' => $root->id, 'title' => '小游戏']);
        Node::create(['node_id' => $root->id, 'title' => '企业微信']);
    }
}
