<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToEvaluateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluate_students', function (Blueprint $table) {
            //
            $table->tinyInteger('status')->default(0)->comment('是否评教 0:未评教 1:已评教');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluate_students', function (Blueprint $table) {
            //
            $table->dropColumn('status');
        });
    }
}
