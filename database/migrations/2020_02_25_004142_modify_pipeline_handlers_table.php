<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPipelineHandlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pipeline_handlers', function (Blueprint $table) {
            $table->string('notice_organizations')->nullable()->comment('支撑组织类型的审批人');
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
        Schema::table('pipeline_handlers', function (Blueprint $table) {
            if (Schema::hasColumn('pipeline_handlers', 'notice_organizations')) {
                $table->dropColumn('notice_organizations');
            }
        });
    }
}
