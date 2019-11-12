<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 保修单表
		if(!Schema::hasTable('wifi_notices')){
			Schema::create('wifi_notices', function (Blueprint $table) {
				$table->bigIncrements('noticeid');
				$table->string('notice_title',60)->nullable()->comment('文档标题');
				$table->longText('notice_content')->comment('文档内容');
				$table->unsignedMediumInteger('sort')->default(1000)->comment('排序');
				$table->dateTime('created_at')->nullable(true)->comment('添加时间');
				$table->dateTime('updated_at')->nullable(true)->comment('更新时间');
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_notices comment 'wifi帮助' ");
		
		
		// 填充数据
		DB::table('wifi_notices')->insert([ 
			0 => [ 'noticeid' => 1 , 'notice_title' => '无法成功连接WiFi接入号' ,'notice_content' => '无法成功连接WiFi接入号' ,] , 
			1 => [ 'noticeid' => 2 , 'notice_title' => '连接WiFi，APP内“点击上线”，但不能上线' ,'notice_content' => '连接WiFi，APP内“点击上线”，但不能上线' , ] , 
			2 => [ 'noticeid' => 3 , 'notice_title' => '已连上WiFi，弹不出登录页面' ,'notice_content' => '已连上WiFi，弹不出登录页面' , ] , 
			3 => [ 'noticeid' => 4 , 'notice_title' => '使用时断网' ,'notice_content' => '使用时断网' ], 	
			4 => [ 'noticeid' => 5 , 'notice_title' => '手机锁屏后断网' ,'notice_content' => '手机锁屏后断网' ], 	
			5 => [ 'noticeid' => 6 , 'notice_title' => '校园网服务协议' ,'notice_content' => '校园网服务协议' ], 	
		]);
		

    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_notices');
    }
}
