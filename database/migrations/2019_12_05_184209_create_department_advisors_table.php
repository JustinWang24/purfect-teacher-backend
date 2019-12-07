<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentAdvisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_advisers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校 ID');
            $table->unsignedInteger('department_id')->comment('系的 id');
            $table->unsignedInteger('department_name')->comment('系的名称');
            $table->unsignedInteger('user_id')->comment('系主任的 USER id');
            $table->unsignedInteger('user_name')->comment('系主任的名字');
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
        Schema::dropIfExists('department_advisers');
    }
}
