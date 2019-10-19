<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institutes')){
            Schema::create('institutes', function (Blueprint $table){
                $table->integerIncrements('id');
                // 该学院(系)关联的学校
                $table->unsignedInteger('school_id')->comment('学校ID');
                // 该学院关联的校区, 如果为 0 表示学校无多个校区的情况
                $table->unsignedInteger('campus_id')->default(0)->comment('校区ID');
                $table->string('name', 100)->comment('学院名称');
                $table->text('description')->comment('学院描述');
                // 最后更新该校区的信息的用户
                $table->unsignedBigInteger('last_updated_by')->default(0);
                $table->timestamps();
                $table->softDeletes();

            });

            DB::statement(" ALTER TABLE institutes comment '学院表' ");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institutes');
    }
}
