<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'body' => $faker->realText(),
        'node_id' => 1,
    ];
});
