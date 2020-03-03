<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitlePipelineNodeOptionsTable extends Migration
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
            $table->string('title')->nullable()->comment('名称');
            $table->string('tips')->nullable()->comment('提示文字');
            if (Schema::hasColumn('pipeline_node_options', 'tip')) {
                $table->dropColumn('tip');
            }
            $table->softDeletes();
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

            if (Schema::hasColumn('pipeline_node_options', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('pipeline_node_options', 'tips')) {
                $table->dropColumn('tips');
            }
            if (Schema::hasColumn('pipeline_node_options', 'title')) {
                $table->dropColumn('title');
            }
            if (!Schema::hasColumn('pipeline_node_options', 'title')) {
                $table->string('tip')->nullable()->comment('提示文字');
            }
        });
    }
}
