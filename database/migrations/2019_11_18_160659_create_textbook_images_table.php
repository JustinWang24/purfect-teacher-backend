<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextbookImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textbook_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('textbook_id')->comment('关联的图书');
            $table->unsignedBigInteger('media_id')->comment('关联的图片');
            $table->unsignedSmallInteger('position')->comment('显示的顺序');
        });



        Schema::table('textbooks', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('textbook_images');
        Schema::table('textbooks', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });
    }
}
