<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('node_id')->index();
            $table->string('title');
            $table->timestamp('excellent_at')->nullable();
            $table->timestamp('pinned_at')->nullable();
            $table->timestamp('frozen_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->json('cache')->nullable(); // followers_count/views_count/comments_count/last_reply_user_name/last_reply_user_id
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
