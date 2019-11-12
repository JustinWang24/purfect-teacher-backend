<?php

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
				//$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifis comment 'wifi产品表' ");
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetable_items');
    }
}
