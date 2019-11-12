<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// wifi全局配置表
		if(!Schema::hasTable('wifi_configs')){
			Schema::create('wifi_configs', function (Blueprint $table) {
				$table->bigIncrements('configid');
				$table->tinyInteger('typeid')->default(0)->comment('类型(1:校园网通知,2:用网须知,3:充值通知,4:套餐说明,5:网络协议)');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->string('config_imgurl1',255)->nullable()->comment('使用网络图片url');
				$table->string('config_imgurl2',255)->nullable()->comment('联合套餐免费用网图片url');
				$table->string('config_imgurl3',255)->nullable()->comment('申请有线图片url');
				$table->tinyInteger('config_imgurl1_status')->default(1)->comment('使用网络状态(0:不显示,1:显示)');
				$table->tinyInteger('config_imgurl2_status')->default(1)->comment('联合套餐免费用网状态(0:不显示,1:显示)');
				$table->tinyInteger('config_imgurl3_status')->default(1)->comment('申请有线状态(0:不显示,1:显示)');
				$table->tinyInteger('status')->default(1)->comment('状态(1:显示,0:不显示)');
				$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifis comment 'wifi全局配置表' ");
		
		// 填充数据
		DB::table('wifi_configs')->insert([ 
			[ 
				'configid' => 1 , 
				'typeid' => 1 , 
				'school_id' => 1 , 
				'campus_id' => 1 , 
				'config_imgurl1' => '/assets/img/mega-img2.jpg' , 
				'config_imgurl2' => '/assets/img/mega-img2.jpg' , 
				'config_imgurl3' => '/assets/img/mega-img2.jpg' , 
				'config_imgurl1_status' => 1 , 
				'config_imgurl2_status' => 1 , 
				'config_imgurl3_status' => 1 ,
				'status' => 1 , 
				'created_at' => '2019-11-13 00:26:33' , 
				'updated_at' => '2019-11-13 00:26:33' ,
			]
		]);
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_configs');
    }
}
