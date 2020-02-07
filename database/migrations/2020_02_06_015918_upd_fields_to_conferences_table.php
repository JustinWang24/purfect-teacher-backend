<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdFieldsToConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conferences', function (Blueprint $table) {
            //
            $table->integer('user_id')->comment('创建人')->change();
            $table->integer('principal_id')->comment('会议负责人');
            $table->tinyInteger('type')->default(0)->comment('类型 0：自定义会议 2:申请会议室');
            $table->string('address')->nullable()->comment('会议地址');
            $table->tinyInteger('status')->default(1)->comment('状态 0未审核 1已通过 2已拒绝');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conferences', function (Blueprint $table) {
            //
            $table->dropColumn('principal_id');
            $table->dropColumn('type');
            $table->dropColumn('address');
            $table->dropColumn('status');
        });
    }
}
