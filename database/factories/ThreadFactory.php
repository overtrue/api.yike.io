<?php

use App\Node;
use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    Node::firstOrCreate(['title' => '微信开发']);
    Node::firstOrCreate(['title' => '小程序', 'node_id' => 1]);

    return [
        'title' => $faker->sentence,
        'node_id' => 2,
    ];
});
