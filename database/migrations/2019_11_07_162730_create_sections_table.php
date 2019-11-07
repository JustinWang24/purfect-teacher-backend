<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校ID');
            $table->unsignedInteger('campus_id')->default(0)->comment('校区ID');
            $table->string('name', 100)->comment('部门名称');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->tinyInteger('level')->default(0)->comment('部门等级');
            $table->string('mobile',11)->default(0)->comment('手机号');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE section comment '部门表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
