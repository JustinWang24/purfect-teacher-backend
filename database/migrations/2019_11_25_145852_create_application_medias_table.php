<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->comment('申请ID');
            $table->integer('media_id')->comment('文件ID');
        });
        DB::statement(" ALTER TABLE application_medias comment '申请和文件关联表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_medias');
    }
}
