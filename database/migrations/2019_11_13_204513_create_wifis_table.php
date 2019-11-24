<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 创建wifi产品
		if(!Schema::hasTable('wifis')){
			Schema::create('wifis', function (Blueprint $table) {
				$table->bigIncrements('wifiid');
				$table->tinyInteger('mode')->default(0)->comment('类型(1:无线,2:有线)');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');				
				$table->tinyInteger('wifi_type')->default(0)->comment('wifi类型id');
				$table->string('wifi_name',50)->nullable()->comment('wifi类型名称');
				$table->unsignedMediumInteger('wifi_days')->default(0)->comment('天数');
				$table->unsignedMediumInteger('wifi_sort')->default(0)->comment('排序');
				$table->decimal('wifi_oprice', 8, 2)->default(0.00)->comment('原始价格');
				$table->decimal('wifi_price', 8, 2)->default(0.00)->comment('支付价格');
				$table->tinyInteger('status')->default(1)->comment('状态(1:显示,0:不显示)');
				$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifis comment 'wifi产品表' ");
		
		// 填充数据
		DB::table('wifis')->insert([ 
			0  => [ 'wifiid' => 1 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 8 , 'wifi_name' => '3年' , 'wifi_days' => 1080 , 'wifi_sort' => 1 , 'wifi_oprice' => '400.00' , 'wifi_price' => '258.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:33' , 'updated_at' => '2019-11-13 00:26:33' ,] , 
			1  => [ 'wifiid' => 2 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 12 , 'wifi_name' => '2年' , 'wifi_days' => 720 , 'wifi_sort' => 1 , 'wifi_oprice' => '298.00' , 'wifi_price' => '258.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:34' , 'updated_at' => '2019-11-13 00:26:34' ,] , 
			2  => [ 'wifiid' => 3 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 7 , 'wifi_name' => '1年' , 'wifi_days' => 360 , 'wifi_sort' => 2 , 'wifi_oprice' => '150.00' , 'wifi_price' => '138.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:24:15' , 'updated_at' => '2019-11-13 00:24:15' ,] , 
			3  => [ 'wifiid' => 4 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 5 , 'wifi_name' => '2个月' , 'wifi_days' => 60 , 'wifi_sort' => 2 , 'wifi_oprice' => '60.00' , 'wifi_price' => '60.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:30' , 'updated_at' => '2019-11-13 00:26:30' ,] , 
			4  => [ 'wifiid' => 5 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 3 , 'wifi_name' => '1学期' , 'wifi_days' => 150 , 'wifi_sort' => 3 , 'wifi_oprice' => '108.00' , 'wifi_price' => '98.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:24:05' , 'updated_at' => '2019-11-13 00:24:05' ,] , 
			5  => [ 'wifiid' => 6 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 6 , 'wifi_name' => '1季度' , 'wifi_days' => 90 , 'wifi_sort' => 3 , 'wifi_oprice' => '80.00' , 'wifi_price' => '69.90' , 'status' => 1 , 'created_at' => '2019-11-13 00:24:21' , 'updated_at' => '2019-11-13 00:24:21' ,] , 
			6  => [ 'wifiid' => 7 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 2 , 'wifi_name' => '1个月' , 'wifi_days' => 30 , 'wifi_sort' => 5 , 'wifi_oprice' => '30.00' , 'wifi_price' => '28.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:24:04' , 'updated_at' => '2019-11-13 00:24:04' ,] , 
			7  => [ 'wifiid' => 8 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 1 , 'wifi_name' => '日卡' , 'wifi_days' => 1 , 'wifi_sort' => 7 , 'wifi_oprice' => '2.00' , 'wifi_price' => '2.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:24:01' , 'updated_at' => '2019-11-13 00:24:01' ,] , 
			8  => [ 'wifiid' => 9 , 'mode' => 2 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 11 , 'wifi_name' => '3年（含无线）' , 'wifi_days' => 1080 , 'wifi_sort' => 8 , 'wifi_oprice' => '450.00' , 'wifi_price' => '298.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:36' , 'updated_at' => '2019-11-13 00:26:36' , ] , 
			9  => [ 'wifiid' => 10 , 'mode' => 2 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 10 , 'wifi_name' => '2年（含无线）' , 'wifi_days' => 720 , 'wifi_sort' => 9 , 'wifi_oprice' => '300.00' , 'wifi_price' => '258.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:36' , 'updated_at' => '2019-11-13 00:26:36' ,] , 
			10 => [ 'wifiid' => 11 , 'mode' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 4 , 'wifi_name' => '1周' , 'wifi_days' => 7 , 'wifi_sort' => 10 , 'wifi_oprice' => '10.00' , 'wifi_price' => '7.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:32' , 'updated_at' => '2019-11-13 00:26:32' ,] , 
			11 => [ 'wifiid' => 12 , 'mode' => 2 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 9 , 'wifi_name' => '1年（含无线）' , 'wifi_days' => 360 , 'wifi_sort' => 10 , 'wifi_oprice' => '150.00' , 'wifi_price' => '138.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:35' , 'updated_at' => '2019-11-13 00:26:35' ,] , 
			12 => [ 'wifiid' => 13 , 'mode' => 2 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 13 , 'wifi_name' => '1学期(含无线)' , 'wifi_days' => 150 , 'wifi_sort' => 11 , 'wifi_oprice' => '108.00' , 'wifi_price' => '98.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:37' , 'updated_at' => '2019-11-13 00:26:37' ,] , 
			13 => [ 'wifiid' => 14 , 'mode' => 2 , 'school_id' => 1 , 'campus_id' => 1 , 'wifi_type' => 14 , 'wifi_name' => '1个月(含无线)' , 'wifi_days' => 30 , 'wifi_sort' => 12 , 'wifi_oprice' => '30.00' , 'wifi_price' => '28.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:38' , 'updated_at' => '2019-11-13 00:26:38' ,] , 
			14 => [ 'wifiid' => 15 , 'mode' => 2 , 'school_id' => 3 , 'campus_id' => 1 , 'wifi_type' => 15 , 'wifi_name' => '1周(含无线)' , 'wifi_days' => 7 , 'wifi_sort' => 13 , 'wifi_oprice' => '10.00' , 'wifi_price' => '7.00' , 'status' => 1 , 'created_at' => '2019-11-13 00:18:19' , 'updated_at' => '2019-11-13 00:18:19' ,] , 
		]);
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifis');
    }
}
