<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsStartToPipelineActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_actions', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('is_start')->default(0)->comment('是否是发起动作');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipeline_actions', function (Blueprint $table) {
            //
            if (Schema::hasColumn('pipeline_actions', 'is_start')) {
                $table->dropColumn('is_start');
            }
        });
    }
}
