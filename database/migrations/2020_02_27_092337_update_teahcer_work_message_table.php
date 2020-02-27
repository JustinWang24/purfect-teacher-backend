<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeahcerWorkMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_teacher_work_logs', function (Blueprint $table) {
            $table->text('content')->change();
        });

        Schema::table('oa_internal_messages', function (Blueprint $table) {
            $table->text('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('oa_teacher_work_logs', function (Blueprint $table) {
            $table->string('content', 255)->change();
       });

       Schema::table('oa_internal_messages', function (Blueprint $table) {
            $table->string('content', 255)->change();
       });
    }
}
