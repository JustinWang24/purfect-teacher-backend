<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMediaIdInCourseMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->default(0)->comment('关联的文件 ID');
//            $table->text('url')->nullable()->comment('文件的链接地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->dropColumn('media_id');
//            $table->dropColumn('url');
        });
    }
}
