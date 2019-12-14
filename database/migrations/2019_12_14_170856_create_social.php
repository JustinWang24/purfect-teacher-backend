<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_follow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('用户id');
            $table->unsignedInteger('to_user_id')->nullable(false)->comment('user_id关注的用户id');
            $table->timestamps();
        });

        Schema::create('social_followed', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('用户id');
            $table->unsignedInteger('from_user_id')->nullable(false)->comment('关注user_id的用户id');
            $table->timestamps();
        });
        Schema::create('social_like', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('用户id');
            $table->unsignedInteger('to_user_id')->nullable(false)->comment('被user_id点赞的用户id');
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
        Schema::dropIfExists('social_follow');
        Schema::dropIfExists('social_followed');
        Schema::dropIfExists('social_like');
    }
}
