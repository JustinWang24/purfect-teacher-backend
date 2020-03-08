<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoProcessedToPipelineFlowsTable extends Migration
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
            $table->unsignedTinyInteger('auto_processed')->default('0')->comment('自动同意');
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
            if (Schema::hasColumn('pipeline_flows', 'auto_processed')) {
                $table->dropColumn('auto_processed');
            }
        });
    }
}
