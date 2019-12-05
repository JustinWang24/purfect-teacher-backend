<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendNoticeToInHandersAndUrgentInActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_handlers', function (Blueprint $table) {
            $table->string('notice_to')->comment('通知人');
        });

        Schema::table('pipeline_actions', function (Blueprint $table) {
            $table->boolean('urgent')->default(false)->comment('是否紧急');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipeline_handlers', function (Blueprint $table) {
            $table->dropColumn('notice_to');
        });

        Schema::table('pipeline_actions', function (Blueprint $table) {
            $table->dropColumn('urgent');
        });
    }
}
