<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->nullable(false)->default(0)->comment('学校');
            $table->string('name')->nullable(false)->comment('社团的名称');
            $table->text('detail')->nullable(false)->comment('社团的介绍');
            $table->string('logo')->nullable(false)->comment('社团logo');
            $table->string('pic1')->nullable(true)->comment('社团申请资料1');
            $table->string('pic2')->nullable(true)->comment('社团申请资料2');
            $table->string('pic3')->nullable(true)->comment('社团申请资料3');
            $table->unsignedInteger('user_id')->nullable(false)->comment('发起人用户id');
            $table->unsignedTinyInteger('status')->nullable(false)->default(0)->comment('状态0未审核，1审核通过');
            $table->timestamps();
        });
        Schema::create('communities_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->nullable(false)->default(0)->comment('学校');
            $table->unsignedInteger('community_id')->nullable(false)->comment('社团的id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('发起人用户id');
            $table->string('user_name')->nullable(false)->comment('发起人用户名');
            $table->unsignedTinyInteger('status')->nullable(false)->default(0)->comment('状态0未通过申请，1通过，2拒绝');
            $table->string('reason')->nullable(false)->comment('加入社团的申请');
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
        Schema::dropIfExists('communities');
        Schema::dropIfExists('communities_member');
    }
}
