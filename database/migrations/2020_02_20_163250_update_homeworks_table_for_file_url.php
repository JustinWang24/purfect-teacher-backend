<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHomeworksTableForFileUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->dropColumn('media_id')->comment('关联的课程');
            $table->string('url')->nullable()->comment('作业的url地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->default(0)->comment('作业内容');
            $table->dropColumn('url');
        });
    }
}
