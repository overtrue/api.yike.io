<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('from', ['wechat', 'github', 'google', 'facebook', 'twitter', 'weibo', 'qq', 'linkedin']);
            $table->string('uid');
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('access_token')->nullable();
            $table->string('access_token_expired_at')->nullable();
            $table->string('access_token_secret')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
