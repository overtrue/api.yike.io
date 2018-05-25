<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('contentable'); // contentable_id, contentable_type
            $table->mediumText('body');
            $table->mediumText('markdown')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_mention', function(Blueprint $table){
            $table->unsignedInteger('content_id');
            $table->unsignedInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
        Schema::dropIfExists('content_mention');
    }
}
