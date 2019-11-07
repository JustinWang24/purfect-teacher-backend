<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Misc\SystemNotification;

class CreateSystemNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->default(0)->comment('针对哪个学校. 0');

            $table->unsignedBigInteger('sender')->default(SystemNotification::FROM_SYSTEM)
                ->comment('发信人 ID, 0 表示系统自动发送');

            $table->unsignedBigInteger('to')->default(SystemNotification::TO_ALL)
                ->comment('收信人ID, 默认 0 表示所有人');

            $table->unsignedSmallInteger('type')->default(SystemNotification::TYPE_NONE)
                ->comment('消息类别, 0表示系统消息');

            $table->unsignedSmallInteger('priority')->default(SystemNotification::PRIORITY_LOW)
                ->comment('消息级别, 0表示一般消息');

            $table->text('content')->comment('消息内容');
            $table->text('next_move')->nullable()->comment('下一步操作');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_notifications');
    }
}
