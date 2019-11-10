<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// wifi订单
        Schema::create('wifi_orders', function (Blueprint $table) {
            $table->bigIncrements('orderid');
            $table->tinyInteger('type')->default(0)->comment('类型(1:充值,2:赠送)');
			$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
			$table->unsignedMediumInteger('campuses_id')->index()->default(0)->comment('校区id');	
			$table->unsignedMediumInteger('user_id')->index()->default(0)->comment('用户ID');
			$table->unsignedBigInteger('wifi_id')->index()->default(0)->comment('wifi_id');
            $table->string('trade_sn',50)->nullable()->comment('订单号');
            $table->tinyInteger('wifi_type')->default(0)->comment('wifi类型');
            $table->string('wifi_name',50)->nullable()->comment('wifi名称');
			$table->unsignedMediumInteger('order_days')->default(0)->comment('购买天数');
			$table->unsignedMediumInteger(order_number)->default(0)->comment('购买数量');
			$table->decimal('order_unitprice', 8, 2)->default(0.00)->comment('wifi单价');
			$table->decimal('order_totalprice', 8, 2)->default(0.00)->comment('支付总价');
			
			
            $table->unsignedMediumInteger('ucid')->default(0)->comment('用户优惠券id');
            $table->unsignedMediumInteger('coupon_id')->default(0)->comment('优惠券id');
            $table->decimal('coupon_money', 8, 2)->default(0.00)->comment('优惠券金额');


            $table->tinyInteger('paymentid')->default(0)->comment('支付方式(1:微信,2:支付宝))');
            $table->string('wifi_name',50)->nullable()->comment('wifi名称');

            
			
            $table->tinyInteger('wifi_type')->default(0)->comment('wifi类型id');
            $table->string('wifi_name',50)->nullable()->comment('wifi类型名称');
			$table->unsignedMediumInteger('wifi_days')->default(0)->comment('天数');
			$table->unsignedMediumInteger('wifi_sort')->default(0)->comment('排序');
			
            $table->tinyInteger('status')->default(1)->comment('状态(1:显示,0:不显示)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi');
    }
}
