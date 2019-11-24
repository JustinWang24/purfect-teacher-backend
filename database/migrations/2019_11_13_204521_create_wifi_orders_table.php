<?php
use Illuminate\Support\Facades\DB;
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
		// wifi订单表
		if(!Schema::hasTable('wifi_orders')){
			Schema::create('wifi_orders', function (Blueprint $table) {
				$table->bigIncrements('orderid');
				$table->string('trade_sn',60)->nullable()->comment('订单号');
				$table->tinyInteger('type')->default(1)->comment('类型(1:充值,2:赠送)');
				$table->tinyInteger('mode')->default(0)->comment('类型(1:无线,2:有线)');
				$table->unsignedMediumInteger('user_id')->default(0)->comment('用户id');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->unsignedMediumInteger('wifi_id')->default(0)->comment('wifi_id');	
				$table->tinyInteger('wifi_type')->default(0)->comment('wifi类型');
				$table->string('wifi_name',50)->nullable()->comment('wifi类型名称');
				$table->unsignedMediumInteger('order_days')->default(0)->comment('天数');
				$table->unsignedMediumInteger('order_number')->default(1)->comment('购买数量');
				$table->decimal('order_unitprice', 8, 2)->default(0.00)->comment('wifi单价');
				$table->decimal('order_totalprice', 8, 2)->default(0.00)->comment('支付总价');
				$table->tinyInteger('paymentid')->default(0)->comment('支付方式(1:微信,2:支付宝,3:钱包支付,4:免支付)');
				$table->string('payment_name',15)->nullable()->comment('支付名称');
				$table->dateTime('pay_time')->nullable(true)->comment('支付时间');
				$table->dateTime('succeed_time')->nullable(true)->comment('支付成功-WIFI充值成功');
				$table->dateTime('defeated_time')->nullable(true)->comment('支付成功-WIFI充值失败');
				$table->dateTime('refund_time')->nullable(true)->comment('支付成功-退款成功时间');
				$table->dateTime('cancle_time')->nullable(true)->comment('取消时间');
				$table->string('wifi_rechargeLog',500)->nullable()->comment('wifi充值日志');
				$table->tinyInteger('wifi_refund')->default(0)->comment('退款(1:已退款)');
				$table->dateTime('wifi_refundtime')->nullable(true)->comment('退款时间');						
				$table->decimal('wifi_refundprice', 8, 2)->default(0.00)->comment('退款金额');		
				$table->string('wifi_operatdesc',500)->nullable()->comment('处理描述');			
				$table->tinyInteger('status')->default(1)->comment('状态(0:关闭,1:待支付,2:支付失败,3:支付成功-WIFI充值中,4:支付成功-WIFI充值成功,5:支付成功-充值失败,6:支付成功-退款成功)');
				$table->tinyInteger('is_chuli')->default(0)->comment('是否处理(0:未处理,1:已处理)');
				$table->dateTime('created_at')->nullable(true)->comment('添加时间');
				$table->dateTime('updated_at')->nullable(true)->comment('更新时间');
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_orders comment 'wifi订单表' ");

    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_orders');
    }
}
