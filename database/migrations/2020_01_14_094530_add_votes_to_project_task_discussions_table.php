<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToProjectTaskDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_project_task_discussions', function (Blueprint $table) {
            //
            $table->integer('reply_user_id')->default(0)->comment('回复人ID 0为全体人员 ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oa_project_task_discussions', function (Blueprint $table) {
            //
            $table->dropColumn('reply_user_id');
        });
    }
}
