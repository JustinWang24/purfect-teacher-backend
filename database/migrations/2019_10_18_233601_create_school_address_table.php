<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSchoolAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('school_address')){

            Schema::create('school_address', function (Blueprint $table){
                $table->integerIncrements('id');
                $table->unsignedInteger('school_id')->comment('学校ID');
                $table->unsignedInteger('campus_id')->comment('校区ID');
                $table->unsignedInteger('pid');
                $table->string('name', 200)->comment('楼名称');
                $table->unsignedTinyInteger('type')->comment('类型 1教学楼 2宿舍楼');
                $table->timestamps();
            });

            DB::statement(" ALTER TABLE school_address comment '学校 楼群表' ");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_address');
    }
}
