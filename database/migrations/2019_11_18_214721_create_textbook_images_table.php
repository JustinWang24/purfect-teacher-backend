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
            $table->unsignedBigInteger('textbook_id');
            $table->unsignedBigInteger('media_id');
            $table->unsignedSmallInteger('position');
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
    }
}
