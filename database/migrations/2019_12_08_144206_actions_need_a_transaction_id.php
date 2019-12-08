<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActionsNeedATransactionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->comment('执行某个流程的唯一识别号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipeline_actions', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
}
