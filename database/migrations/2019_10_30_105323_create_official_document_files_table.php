<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficialDocumentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_document_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('official_document_id')->comment('公文ID');
            $table->string('path')->comment('路径');
            $table->string('size')->comment('文件大小');
            $table->string('format')->comment('资源格式');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE official_document_files comment '公文附件表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_document_files');
    }
}
