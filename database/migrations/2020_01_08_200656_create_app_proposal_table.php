<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_proposals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->bigInteger('type')->comment('类型');
            $table->string('content')->comment('反馈内容');
            $table->timestamps();
        });

        Schema::create('app_proposal_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('app_proposal_id')->comment('反馈ID');
            $table->string('path')->comment('图片地址');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_proposals');
        Schema::dropIfExists('app_proposal_images');
    }
}
