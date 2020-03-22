<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloseedToPipelineFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_flows', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('closed')->default(0)->comment('是否关闭 1-关闭');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipeline_flows', function (Blueprint $table) {
            //
            if (Schema::hasColumn('pipeline_flows', 'closed')) {
                $table->dropColumn('closed');
            }
        });
    }
}
