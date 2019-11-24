<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiOrdersLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// wifi订单表
		if(!Schema::hasTable('wifi_orders_locations')){
			Schema::create('wifi_orders_locations', function (Blueprint $table) {
				$table->bigIncrements('locationid');
				$table->unsignedMediumInteger('orderid')->comment('wifi订单id');
				$table->unsignedMediumInteger('user_id')->comment('用户id');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->unsignedMediumInteger('addressoneid')->default(0)->comment('有线(宿舍楼id)');	
				$table->unsignedMediumInteger('addresstwoid')->default(0)->comment('有线(房间id)');	
				$table->unsignedMediumInteger('addressthreeid')->default(0)->comment('有线(房间id)');	
				$table->unsignedMediumInteger('addressfourid')->default(0)->comment('有线(端口id)');	
				$table->string('addressoneid_name',50)->nullable()->comment('有线(宿舍楼名称)');
				$table->string('addresstwoid_name',50)->nullable()->comment('有线(楼层名称)');
				$table->string('addressthreeid_name',50)->nullable()->comment('有线(房间名称)');
				$table->string('addressfourid_name',50)->nullable()->comment('有线(端口名称)');
				$table->tinyInteger('status')->default(0)->comment('状态(0:未开通,1:已开通)');
				$table->dateTime('created_at')->nullable(true)->comment('添加时间');
				$table->dateTime('updated_at')->nullable(true)->comment('更新时间');
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_orders_locations comment 'wifi订单有线地址表' ");

    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_orders_locations');
    }
}
