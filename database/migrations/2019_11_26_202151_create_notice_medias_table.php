<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('notice_id')->comment('通知公告ID');
            $table->unsignedInteger('media_id')->comment('附件ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notice_medias');
    }
}
