<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invited_by')->comment('邀请的人');
            $table->unsignedSmallInteger('status')->default(1)->comment('状态');
            $table->string('name',30)->comment('访客姓名');
            $table->string('mobile',11)->comment('访客电话');
            $table->string('vehicle_license',8)->nullable()->comment('车牌');
            $table->text('reason')->comment('到访是由');

            $table->dateTime('scheduled_at')->nullable()->comment('预约的访问日期');
            $table->dateTime('arrived_at')->nullable()->comment('实际到达时间');
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
        Schema::dropIfExists('visitors');
    }
}
