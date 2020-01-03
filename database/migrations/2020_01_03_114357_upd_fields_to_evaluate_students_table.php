<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdFieldsToEvaluateStudentsTable extends Migration
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
            $table->tinyInteger('score')->default(0)->comment('评教总得分');
            $table->string('desc')->comment('描述')->nullable();

        });

        Schema::table('evaluate_teacher_records', function (Blueprint $table) {
            //
            $table->dropColumn('desc');

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
            $table->dropColumn('score');
            $table->dropColumn('desc');
        });

        Schema::table('evaluate_teacher_records', function (Blueprint $table) {
            //
            $table->string('desc')->comment('描述')->nullable();
        });
    }
}
