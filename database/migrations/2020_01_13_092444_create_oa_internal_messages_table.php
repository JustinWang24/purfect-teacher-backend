<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaInternalMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_internal_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('发件人ID');
            $table->string('collect_user_id')->comment('收信人ID');
            $table->string('collect_user_name')->comment('收信人');
            $table->string('title',100)->comment('标题');
            $table->string('content')->comment('内容');
            $table->tinyInteger('type')->comment('类型, 1未读, 2已读, 3已发送, 4草稿箱');
            $table->tinyInteger('is_relay')->default(0)->comment('是否转发 0未转发, 1转发');
            $table->tinyInteger('is_file')->default(0)->comment('是否有附件 0无, 1有');
            $table->unsignedInteger('message_id')->nullable()->comment('转发的ID');
            $table->tinyInteger('status')->default(1)->comment('状态 0不显示 1显示');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_internal_messages comment '内部信表' ");

        Schema::create('oa_internal_message_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('message_id')->comment('信件ID');
            $table->string('path')->comment('图片路径');
            $table->string('name')->comment('附件名');
            $table->string('type', 50)->comment('附件类型');
            $table->string('size', 50)->comment('附件大小');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_internal_message_files comment '内部信附件表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_internal_messages');
        Schema::dropIfExists('oa_internal_message_files');
    }
}
