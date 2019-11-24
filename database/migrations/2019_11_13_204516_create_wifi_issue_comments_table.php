<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiIssueCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 保修单评价表
		if(!Schema::hasTable('wifi_issue_comments')){
			Schema::create('wifi_issue_comments', function (Blueprint $table) {
				$table->bigIncrements('commentid');
				$table->unsignedMediumInteger('user_id')->index()->default(0)->comment('用户id');
				$table->unsignedMediumInteger('school_id')->index()->default(0)->comment('学校id');
				$table->unsignedMediumInteger('campus_id')->index()->default(0)->comment('校区id');	
				$table->unsignedMediumInteger('issueid')->index()->default(0)->comment('保修单id');	
				$table->tinyInteger('comment_service')->default(0)->comment('服务态度');
				$table->tinyInteger('comment_worders')->default(0)->comment('工作效率');
				$table->tinyInteger('comment_efficiency')->default(0)->comment('满意度');
				$table->string('comment_content',300)->nullable()->comment('评论内容');
				$table->tinyInteger('status')->default(1)->comment('状态(1:显示,0:不显示)');
				$table->timestamps();
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_issue_comments comment '保修单评价表' ");
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_issue_comments');
    }
}
