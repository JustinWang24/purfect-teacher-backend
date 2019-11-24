<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMajorTableWithOpenAndSeats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('majors', function (Blueprint $table) {
            $table->boolean('open')->comment('是否打开预招的功能');
            $table->boolean('hot')->comment('是否为热门课程');
            $table->unsignedMediumInteger('seats')->comment('招生的人数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('majors', function (Blueprint $table) {
            if(Schema::hasColumn('majors','open')){
                $table->dropColumn('open');
                $table->dropColumn('seats');
                $table->dropColumn('hot');
            }
        });
    }
}
