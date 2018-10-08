<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn("threads", "popular_at")) {
            Schema::table("threads", function($table) {
                $table->timestamp("popular_at")
                    ->nullable()
                    ->comment('是否加精')
                    ->after('excellent_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn("threads", "popular_at")) {
            Schema::table("threads", function($table) {
                $table->dropColumn("popular_at");
            });
        }
    }
}
