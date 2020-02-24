<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPipelineNodeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pipeline_node_options', function (Blueprint $table) {
            $table->string('tip')->nullable()->comment('提示文字');
            $table->unsignedTinyInteger('required')->default(0)->comment('必填 1-是 0-否');
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
        Schema::table('pipeline_node_options', function (Blueprint $table) {
            if (Schema::hasColumn('pipeline_node_options', 'tip')) {
                $table->dropColumn('tip');
            }
            if (Schema::hasColumn('pipeline_node_options', 'required')) {
                $table->dropColumn('required');
            }
        });
    }
}
