<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
             $table->unsignedSmallInteger('asterisk')->default(0)->comment('是否有星标 0:无 1:是');
             $table->unsignedSmallInteger('public')->default(0)->comment('公开 0:不公开 1:公开');
             $table->unsignedSmallInteger('type')->default(0)
                 ->comment('目录的类别 目录的类别 0:整个系统的根目录 1.学校的根目录 2.用户的根目录 3.学校子目录 4.用户子目录')
                 ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
