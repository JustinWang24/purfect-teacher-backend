<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnhanceBuildingAndRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->text('description')->comment('建筑物的描述');
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedSmallInteger('seats')->default(1)->comment('房间可以容纳的人数');
            $table->text('description')->nullable()->comment('房间的描述');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
