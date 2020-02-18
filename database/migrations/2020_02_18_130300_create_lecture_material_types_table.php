<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectureMaterialTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture_material_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id');
            $table->string('name',30);
        });
        DB::statement(" ALTER TABLE lecture_material_types comment '学习资料类型' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecture_material_types');
    }
}
