<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id')->nullable();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('banner')->nullable();
            $table->string('description')->nullable();
            $table->json('settings')->nullable(); // title_color/description_color
            $table->json('cache')->nullable(); // threads_count/views_count/followers_count
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
    }
}
