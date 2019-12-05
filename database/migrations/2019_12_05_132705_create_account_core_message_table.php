<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCoreMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_core_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('学校ID');
            $table->unsignedBigInteger('campus_id')->comment('校区ID');
            $table->string('content')->comment('消息内容');
            $table->smallInteger('status')->default(0)->comment('状态 0:待发布 1:已发布');
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
        Schema::dropIfExists('account_core_messages');
    }
}
