<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenMajorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_majors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校ID');
            $table->unsignedInteger('major_id')->comment('专业ID');
            $table->unsignedMediumInteger('number')->default(0)->comment('招收人数');
            $table->decimal('fee',8,2)->default(0.00)->comment('学费');
            $table->tinyInteger('is_popular')->default(0)->comment('是否热门 0:否 1:是');
            $table->tinyInteger('status')->default(0)->comment('状态 0:关闭 1:开启');
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
        Schema::dropIfExists('open_majors');
    }
}
