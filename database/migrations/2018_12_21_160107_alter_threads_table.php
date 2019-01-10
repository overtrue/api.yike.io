<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterThreadsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->timestamp('popular_at')->nullable()->comment('加精时间')->create();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropColumn('popular_at');
        });
    }
}
