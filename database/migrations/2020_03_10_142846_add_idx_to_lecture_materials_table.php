<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdxToLectureMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lecture_materials', function (Blueprint $table) {
            //
            $table->integer('idx')->default(0)->comment('课节');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lecture_materials', function (Blueprint $table) {
            //
            $table->dropColumn('idx');
        });
    }
}
