<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollectUserIdToOaInternalMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_internal_messages', function (Blueprint $table) {
            $table->string('collect_user_id', 100)->nullable()->comment('多个收件人ID')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oa_internal_messages', function (Blueprint $table) {
            $table->tinyInteger('collect_user_id')->nullable()->comment('多个收件人ID')->change();
        });
    }
}
