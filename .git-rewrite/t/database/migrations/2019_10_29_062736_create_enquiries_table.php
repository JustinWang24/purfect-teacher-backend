<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Misc\Enquiry;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('school_id')->comment('归属于哪个学校');
            $table->unsignedBigInteger('user_id')->comment('由谁发起');
            $table->unsignedBigInteger('grade_user_id')->comment('发起人的学校信息');
            $table->unsignedSmallInteger('type')->default(0)->comment('请求的种类: 请假, 报销等');

            $table->unsignedBigInteger('to_user_id')->default(0)
                ->comment('表示直接发给这个人进行审批');
            $table->unsignedBigInteger('copy_to_user_id')->default(0)
                ->comment('表示同时抄送发给这个人进行审批');

            $table->unsignedBigInteger('approved_by')->comment('最终的审批人, 决策人');

            $table->string('title')->comment('审批的 Title');
            $table->dateTime('start_at')->nullable()->comment('请求事件所关联的开始日期');
            $table->dateTime('end_at')->nullable()->comment('请求事件所关联的结束日期');

            $table->unsignedSmallInteger('status')->default(Enquiry::STATUS_WAITING)
                ->comment('审批的状态');

            $table->unsignedSmallInteger('result')->default(Enquiry::RESULT_REJECTED)
                ->comment('审批的结果');

            $table->text('details')->nullable()->comment('请求的描述, 申请人填写');
            $table->text('notes')->nullable()->comment('审批最终结果的标注');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
}
