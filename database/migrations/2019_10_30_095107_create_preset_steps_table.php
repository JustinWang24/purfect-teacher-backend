<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresetStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preset_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name','100')->nullable()->comment('步骤名称');
            $table->string('describe')->comment('步骤描述');
            $table->string('level')->nullable()->comment('步骤等级');
            $table->string('key')->nullable()->comment('代表实现此步骤的类,如何进行实例化');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE preset_steps comment '系统预置步骤表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preset_steps');
    }
}
