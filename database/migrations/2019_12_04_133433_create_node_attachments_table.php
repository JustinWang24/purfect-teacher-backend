<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_node_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('node_id')->comment('关联的流程步骤');
            $table->unsignedBigInteger('media_id')->comment('关联的文件 ID');
            $table->string('file_name')->comment('文件名');
            $table->string('url')->comment('文件的链接地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipeline_node_attachments');
    }
}
