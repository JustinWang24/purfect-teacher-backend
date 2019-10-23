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
                $table->increments('id');
                $table->integer('conference_id')->comment('会议ID');
                $table->integer('media_id')->comment('媒体ID');
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
