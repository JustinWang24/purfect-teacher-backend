<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPipelineFlowsInBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pipeline_flows', function (Blueprint $table) {
            if (!Schema::hasColumn('pipeline_flows', 'business')) {
                $table->string('business')->nullable()->comment('关联业务');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('pipeline_flows', function (Blueprint $table) {
            if (Schema::hasColumn('pipeline_flows', 'business')) {
                $table->dropColumn('business');
            }
        });
    }
}
