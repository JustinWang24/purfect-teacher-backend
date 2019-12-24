<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToAttendancesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            $table->mediumInteger('year')->comment('年');
            $table->mediumInteger('term')->comment('学期');
            $table->tinyInteger('type')->default('1')->comment('类型 1:云班牌 ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('term');
            $table->dropColumn('type');
        });
    }
}
