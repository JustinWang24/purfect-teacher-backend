<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('application_type_id')->comment('申请类型');
            $table->text('reason')->comment('申请理由');
            $table->enum('census',[1,2])->nullable()->comment('户口类型 1:农村 2:城市');
            $table->tinyInteger('family_population')->nullable()->comment('家庭人口');
            $table->smallInteger('general_income')->nullable()->comment('家庭月总收入');
            $table->smallInteger('per_capita_income')->nullable()->comment('人均收入');
            $table->string('income_source')->nullable()->comment('收入来源');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->integer('last_update_by')->nullable()->comment('审核人');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE applications comment '申请表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
