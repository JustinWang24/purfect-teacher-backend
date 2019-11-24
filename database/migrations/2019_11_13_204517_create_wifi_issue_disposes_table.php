<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiIssueDisposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 保修单处理表
		if(!Schema::hasTable('wifi_issue_disposes')){
			Schema::create('wifi_issue_disposes', function (Blueprint $table) {
				$table->bigIncrements('disposeid');
				$table->unsignedMediumInteger('issueid')->index()->default(0)->comment('保修单id');	
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->unsignedMediumInteger('typeone_id')->index()->default(0)->comment('一级问题id');	
				$table->string('typeone_name',50)->nullable()->comment('一级问题名称');	
				$table->unsignedMediumInteger('typetwo_id')->index()->default(0)->comment('二级问题id');	
				$table->string('typetwo_name',50)->nullable()->comment('二级问题名称');	
				$table->string('admin_desc',2000)->nullable()->comment('处理回复描述');					
				$table->tinyInteger('status')->default(1)->comment('状态(1:待处理，2:已接单，3:已完成,，4:已取消，5:已关闭)');
				$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_issue_disposes comment '保修单处理表' ");
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_issue_disposes');
    }
}
