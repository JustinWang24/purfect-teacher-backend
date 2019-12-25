<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCodeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_code_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('facility_id')->comment('设备ID');
            $table->tinyInteger('type')->default(1)->comment('类型 1:验证 2:支出');
            $table->string('desc')->comment('描述')->nullable();
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE user_code_records comment '用户二维码使用记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_code_records');
    }
}
