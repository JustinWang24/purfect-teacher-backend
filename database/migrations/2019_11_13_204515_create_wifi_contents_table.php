<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 创建wifi产品
		if(!Schema::hasTable('wifi_contents')){
			Schema::create('wifi_contents', function (Blueprint $table) {
				$table->bigIncrements('contentid');
				$table->tinyInteger('typeid')->default(0)->comment('类型(1:校园网通知,2:用网须知,3:充值通知,4:套餐说明,5:网络协议)');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');		
				$table->longText('content')->comment('内容');
				$table->tinyInteger('status')->default(1)->comment('状态(1:显示,0:不显示)');
				$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_contents comment 'wifi网络信息表' ");
		
		// 填充数据
		DB::table('wifi_contents')->insert([ 
		  [ 'contentid' => 1 , 'typeid' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'content' => '校园网通知' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:33' , 'updated_at' => '2019-11-13 00:26:33' ,] , 
		  [ 'contentid' => 2 , 'typeid' => 2 , 'school_id' => 1 , 'campus_id' => 1 , 'content' => '用网须知' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:33' , 'updated_at' => '2019-11-13 00:26:33' ,] , 
		  [ 'contentid' => 3 , 'typeid' => 3 , 'school_id' => 1 , 'campus_id' => 1 , 'content' => '充值通知' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:33' , 'updated_at' => '2019-11-13 00:26:33' ,] , 
		  [ 'contentid' => 4 , 'typeid' => 4 , 'school_id' => 1 , 'campus_id' => 1 , 'content' => '套餐说明' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:33' , 'updated_at' => '2019-11-13 00:26:33' ,] , 
		  [ 'contentid' => 5 , 'typeid' => 5 , 'school_id' => 1 , 'campus_id' => 1 , 'content' => '网络协议' , 'status' => 1 , 'created_at' => '2019-11-13 00:26:33' , 'updated_at' => '2019-11-13 00:26:33' ,] , 			
		]);
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_contents');
    }
}
