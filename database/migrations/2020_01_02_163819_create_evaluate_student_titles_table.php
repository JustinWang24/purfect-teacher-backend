<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluateStudentTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate_student_titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('school_id')->comment('学校ID');
            $table->string('title')->comment('评学标题');
            $table->unsignedTinyInteger('status')->default(0)->comment('0 关闭，1开启');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE evaluate_student_titles comment '评学表' ");
        DB::statement(" ALTER TABLE evaluate_student_records comment '评学记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluate_student_titles');
    }
}
