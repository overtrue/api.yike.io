<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('icon')->nullable();
            $table->string('color', 12)->default('#d4d4d4');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->integer('tag_id')->unsigned();
            $table->integer('taggable_id')->unsigned();
            $table->string('taggable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('taggables');
        Schema::dropIfExists('tags');
    }
}
