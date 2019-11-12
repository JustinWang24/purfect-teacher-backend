<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiUserTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 用户wifi时长和电话套餐表
		if(!Schema::hasTable('wifi_user_times')){
			Schema::create('wifi_user_times', function (Blueprint $table) {
				$table->bigIncrements('timesid');
				$table->unsignedMediumInteger('user_id')->comment('用户id');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->dateTime('user_wifi_time')->nullable(true)->comment('wifi到期时间');
				$table->string('user_wifi_name',50)->nullable()->comment('wifi账号');
				$table->string('user_wifi_password',50)->nullable()->comment('wifi密码');
				$table->dateTime('user_cable_etime')->nullable(true)->comment('wifi有线开通结束时间');
				$table->tinyInteger('user_mobile_source')->default(0)->comment('运营商(1:移动,2:联通,3:电信,4:其他)');
				$table->string('user_mobile_phone',15)->nullable()->comment('套餐手机号');		
				$table->string('user_mobile_password',15)->nullable()->comment('套餐手机号密码');		
				$table->string('user_phone_package_desc',300)->nullable()->comment('套餐描述信息');	
				$table->tinyInteger('status')->default(1)->comment('wifi是否可用(0:不可用,1:可用)');
				$table->dateTime('created_at')->nullable(true)->comment('添加时间');
				$table->dateTime('updated_at')->nullable(true)->comment('更新时间');
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_user_times comment '用户wifi时长和电话套餐表' ");

    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_user_times');
    }
}
