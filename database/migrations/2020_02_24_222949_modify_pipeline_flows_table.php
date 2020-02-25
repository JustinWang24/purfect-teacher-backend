<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPipelineFlowsTable extends Migration
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
            $table->string('copy_uids')->nullable()->comment('抄送人');
            $table->string('business')->nullable()->comment('关联业务');
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
            if (Schema::hasColumn('pipeline_flows', 'copy_uids')) {
                $table->dropColumn('copy_uids');
            }
            if (Schema::hasColumn('pipeline_flows', 'business')) {
                $table->dropColumn('business');
            }
        });
    }
}
