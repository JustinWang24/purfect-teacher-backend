<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferencesMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('conferences_medias')) {
            Schema::create('conferences_medias', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('conference_id')->comment('会议ID');
                $table->foreign('conference_id')->references('id')->on('conferences');
                $table->unsignedBigInteger('media_id')->comment('媒体ID');
                $table->foreign('media_id')->references('id')->on('medias');
                $table->timestamps();
            });
        }
        DB::statement(" ALTER TABLE conferences_medias comment '会议媒体关联表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conferences_medias');
    }
}
