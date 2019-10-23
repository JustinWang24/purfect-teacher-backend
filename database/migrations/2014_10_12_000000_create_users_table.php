<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uuid')->index();
                $table->string('name')->nullable()->comment('名称');
                $table->string('mobile',11)->unique()->comment('手机号');
                $table->string('password')->comment('密码');
                $table->string('email')->unique()->nullable()->comment('邮箱');
                $table->unsignedSmallInteger('type')->comment('用户类型')->default(User::TYPE_STUDENT);;
                $table->unsignedSmallInteger('status')->comment('账户状态')->default(User::STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED); // 账户状态, 默认为手机号未验证
                $table->timestamp('mobile_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });

            DB::statement(" ALTER TABLE users comment '用户表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('users');
    }
}
