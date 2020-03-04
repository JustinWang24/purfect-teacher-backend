<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPipelineNodeOptionsInExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_node_options', function (Blueprint $table) {
            if (!Schema::hasColumn('pipeline_node_options', 'extra')) {
                $table->text('extra')->nullable()->comment('扩展字段');
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
        Schema::table('pipeline_node_options', function (Blueprint $table) {
            if (Schema::hasColumn('pipeline_node_options', 'extra')) {
                $table->dropColumn('extra');
            }

        });
    }
}
