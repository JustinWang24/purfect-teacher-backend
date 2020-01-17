<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdaetOrInteranilMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_internal_messages', function (Blueprint $table) {
            $table->string('message_id', 50)->nullable()->comment('转发的ID')->change();
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
            $table->integer('message_id')->nullable()->comment('转发的ID')->change();
        });
    }
}
