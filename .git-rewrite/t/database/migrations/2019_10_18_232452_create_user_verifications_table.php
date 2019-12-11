<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_verifications')){

            Schema::create('user_verifications', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->default(0);
                // 验证码用途: 默认值为用户注册
                $table->unsignedSmallInteger('purpose')
                    ->default(1); // 默认值应该定义成常量
                $table->string('mobile', 11)->comment('手机号');    // 用户手机号
                $table->string('code',6)->comment('验证码');        // 用户验证码
                $table->timestamp('created_at');        // 用来计算验证码过期时间
            });

            DB::statement(" ALTER TABLE user_verifications comment '验证码表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifications');
    }
}
