<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school')->comment('关联的学校');
            $table->unsignedInteger('grade_id')->comment('关联的学校');
            $table->unsignedInteger('adviser_id')->comment('班主任 ID');
            $table->unsignedInteger('adviser_name')->comment('班主任名字');
            $table->unsignedInteger('monitor_id')->comment('班长 ID');
            $table->unsignedInteger('monitor_name')->comment('班长名字');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_managers');
    }
}
