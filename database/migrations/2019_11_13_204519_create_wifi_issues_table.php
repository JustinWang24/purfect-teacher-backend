<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 保修单表
		if(!Schema::hasTable('wifi_issues')){
			Schema::create('wifi_issues', function (Blueprint $table) {
				$table->bigIncrements('issueid');
				$table->unsignedMediumInteger('user_id')->index()->default(0)->comment('用户id');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->string('trade_sn',60)->nullable()->comment('订单号');
				$table->unsignedMediumInteger('typeone_id')->default(0)->comment('大类型ID');
				$table->string('typeone_name',50)->nullable()->comment('大类型名称');
				$table->unsignedMediumInteger('typetwo_id')->default(0)->comment('小类型ID');
				$table->string('typetwo_name',50)->nullable()->comment('小类型名称');
				$table->string('issue_name',20)->nullable()->comment('故障申请联系人姓名');
				$table->string('issue_mobile',15)->nullable()->comment('故障申请联系人电话');
				$table->string('issue_desc',2000)->nullable()->comment('故障问题描述');
				$table->unsignedMediumInteger('addressoneid')->index()->default(0)->comment('地址一级id');
				$table->unsignedMediumInteger('addresstwoid')->index()->default(0)->comment('地址二级id');
				$table->unsignedMediumInteger('addressthreeid')->index()->default(0)->comment('地址三级id');	
				$table->string('addr_detail',150)->nullable()->comment('保修详细地址');
				$table->unsignedMediumInteger('adminid')->index()->default(0)->comment('后台处理人');	
				$table->string('admin_name',10)->nullable()->comment('后台处理人姓名');
				$table->string('admin_mobile',15)->nullable()->comment('后台处理人手机号');
				$table->string('admin_desc',2000)->nullable()->comment('后台处理人反馈');
				$table->tinyInteger('is_comment')->default(1)->comment('是否评价(1:未评价,2:已评价)');
				$table->dateTime('jiedan_time')->nullable()->comment('接单时间');
				$table->dateTime('chulis_time')->nullable()->comment('处理时间');
				$table->tinyInteger('status')->default(1)->comment('状态,1:待处理,2:已接单，3:已完成,4:已取消,5:已关闭.)');
				$table->dateTime('created_at')->nullable()->comment('添加时间');
				$table->dateTime('updated_at')->nullable()->comment('更新时间');
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_issues comment '保修单表' ");

    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_issues');
    }
}
