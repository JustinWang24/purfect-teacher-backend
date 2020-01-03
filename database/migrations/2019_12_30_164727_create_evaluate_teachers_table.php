<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('user_id')->comment('老师ID');
            $table->date('year')->comment('学年');
            $table->tinyInteger('type')->default(1)->comment('学期 1:上学期 2:下学期');
            $table->integer('group_id')->comment('教研组ID');
            $table->tinyInteger('score')->default(0)->comment('平均数');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE evaluate_teachers comment '评价老师总表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluate_teachers');
    }
}
