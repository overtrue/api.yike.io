<?php

namespace Tests;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $faker;

    /**
     * Set up the test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();

        config(['throttle.thread.create' => 50]);
    }

    /**
     * Reset the migrations.
     */
    public function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
