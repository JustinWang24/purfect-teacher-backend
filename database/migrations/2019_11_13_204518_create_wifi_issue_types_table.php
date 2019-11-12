<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiIssueTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 保修类型表
		if(!Schema::hasTable('wifi_issue_types')){
			Schema::create('wifi_issue_types', function (Blueprint $table) {
				$table->bigIncrements('typeid');
				$table->string('type_name',50)->nullable()->comment('类型名称');					
				$table->unsignedMediumInteger('type_pid')->index()->default(0)->comment('父id');		
				$table->unsignedMediumInteger('purpose')->index()->default(1)->comment('用途（1:客户选择故障类型,2:维修人员类型选择故障)');
				$table->tinyInteger('status')->default(1)->comment('状态(0:不显示,1:显示)');
				$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_issue_types comment '保修类型表' ");
		
		// 填充数据
		DB::table('wifi_issue_types')->insert([ 
			0 => [ 'typeid' => 1 , 'type_name' => '网速慢' , 'type_pid' => 0 , ] , 
			1 => [ 'typeid' => 2 , 'type_name' => '断网' , 'type_pid' => 0 , ] , 
			2 => [ 'typeid' => 3 , 'type_name' => '无法充值' , 'type_pid' => 0 , ] , 
			3 => [ 'typeid' => 4 , 'type_name' => '一直显示充值中' , 'type_pid' => 3 , ] , 
			4 => [ 'typeid' => 5 , 'type_name' => '搜不到网络，无法连接' , 'type_pid' => 2 , ] , 
			5 => [ 'typeid' => 6 , 'type_name' => '网络不稳定，频繁掉线' , 'type_pid' => 2 , ] , 
			6 => [ 'typeid' => 7 , 'type_name' => '打游戏特别卡' , 'type_pid' => 1 , ] , 
			7 => [ 'typeid' => 8 , 'type_name' => '视频无法观看' , 'type_pid' => 1 , ] , 
			8 => [ 'typeid' => 9 , 'type_name' => '开通有线网络' , 'type_pid' => 0 , ] , 
			9 => [ 'typeid' => 10 , 'type_name' => '1号端口' , 'type_pid' => 9 , ] , 
			10 => [ 'typeid' => 11 , 'type_name' => '2号端口' , 'type_pid' => 9 , ] , 
			11 => [ 'typeid' => 12 , 'type_name' => '3号端口' , 'type_pid' => 9 , ] , 
			12 => [ 'typeid' => 13 , 'type_name' => '4号端口' , 'type_pid' => 9 , ] , 
			13 => [ 'typeid' => 14 , 'type_name' => '网络侧问题' , 'type_pid' => 0 , ] , 
			14 => [ 'typeid' => 15 , 'type_name' => '线路侧问题' , 'type_pid' => 0 , ] , 
			15 => [ 'typeid' => 16 , 'type_name' => '用户侧问题' , 'type_pid' => 0 , ] , 
			16 => [ 'typeid' => 17 , 'type_name' => '上联传输线路故障' , 'type_pid' => 14 , ] , 
			17 => [ 'typeid' => 18 , 'type_name' => '机房设备故障' , 'type_pid' => 14 , ] , 
			18 => [ 'typeid' => 19 , 'type_name' => '机房设备数据配置问题' , 'type_pid' => 14 , ] , 
			19 => [ 'typeid' => 20 , 'type_name' => '楼道设备故障' , 'type_pid' => 14 , ] , 
			20 => [ 'typeid' => 21 , 'type_name' => '楼道设备断电' , 'type_pid' => 14 , ] , 
			21 => [ 'typeid' => 22 , 'type_name' => 'APP设备故障' , 'type_pid' => 14 , ] , 
			22 => [ 'typeid' => 23 , 'type_name' => '用户自好' , 'type_pid' => 14 , ] , 
			23 => [ 'typeid' => 24 , 'type_name' => '光缆跳纤终端' , 'type_pid' => 15 , ] , 
			24 => [ 'typeid' => 25 , 'type_name' => '光缆跳纤衰减' , 'type_pid' => 15 , ] , 
			25 => [ 'typeid' => 26 , 'type_name' => '综合布线问题' , 'type_pid' => 15 , ] , 
			26 => [ 'typeid' => 27 , 'type_name' => '用户手机问题' , 'type_pid' => 16 , ] , 
			27 => [ 'typeid' => 28 , 'type_name' => '用户电脑问题' , 'type_pid' => 16 , ] , 
			28 => [ 'typeid' => 29 , 'type_name' => '用户端水晶头问题' , 'type_pid' => 16 , ] , 
			29 => [ 'typeid' => 30 , 'type_name' => '用户内部布线问题' , 'type_pid' => 16 , ] , 
			30 => [ 'typeid' => 31 , 'type_name' => '用户账号/密码问题' , 'type_pid' => 16 , ] , ]
		);
		
		
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_issue_types');
    }
}
