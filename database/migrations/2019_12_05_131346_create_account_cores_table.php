<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_cores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('学校ID');
            $table->unsignedBigInteger('campus_id')->comment('校区ID');
            $table->decimal('fictitious_money',8,2)->default(0.00)->comment('到账虚拟金额');
            $table->decimal('actual_money',8,2)->default(0.00)->comment('普通用户需支付的金额');
            $table->decimal('vip_money',8,2)->default(0.00)->comment('vip用户需支付的金额');
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
        Schema::dropIfExists('account_cores');
    }
}
