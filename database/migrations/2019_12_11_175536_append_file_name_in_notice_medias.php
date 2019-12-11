<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendFileNameInNoticeMedias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notice_medias', function (Blueprint $table) {
            $table->string('file_name')->comment('附件文件名');
            $table->string('url')->comment('附件文件名');
        });
        Schema::table('notices', function (Blueprint $table) {
            $table->string('image')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notice_medias', function (Blueprint $table) {
            $table->dropColumn('file_name');
            $table->dropColumn('url');
        });
    }
}
