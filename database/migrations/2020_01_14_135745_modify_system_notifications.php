<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySystemNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->dropColumn('money');
            $table->unsignedInteger('category')->nullable(true)->comment('消息种类 对应关系见model')->change();
            $table->string('app_extra')->nullable(true)->comment('前端跳转对应栏目所需key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('system_notifications', 'money')) {
                $table->string('money')->nullable(true)->comment('钱数，没有则不填写');
            }
            if (Schema::hasColumn('system_notifications', 'app_extra')) {
                $table->dropColumn('app_extra');
            }
            $table->unsignedInteger('category')->nullable(true)->comment('消息种类：1 易码通， 2 校园网， 3通知，4公告，5检查，6课件，7课程，8考试， 9招生， 10申请，11订单，12值周，13就业，14选课，15会员，16签到， 17优惠券，18绿色通道，19退费，20消息')->change();
        });
    }
}
