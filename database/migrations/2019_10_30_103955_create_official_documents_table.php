<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficialDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', '50')->comment('公文标题');
            $table->string('content');
            $table->unsignedBigInteger('send_department_id')->comment('发送部门ID');
            $table->unsignedBigInteger('receive_department_id')->comment('接收部门ID');
            // .... 等等以后按照需求添加
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE official_documents comment '公文表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_documents');
    }
}
