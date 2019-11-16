<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->unsignedSmallInteger('asterisk')->default(0)->comment('是否有星标 0:无 1:是');
            $table->dateTime('updated_at');
            $table->unsignedSmallInteger('click')->default(0)->comment('点击次数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->dropColumn('asterisk');
            $table->dropColumn('updated_at');
            $table->dropColumn('click');
        });
    }
}
