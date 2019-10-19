<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCampusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('campuses')) {
            Schema::create('campuses', function (Blueprint $table) {
                $table->integerIncrements('id');
                // 该校区关联的学校
                $table->unsignedInteger('school_id')->comment('学校ID');
                $table->string('name', 100)->comment('校区名称');
                $table->text('description')->comment('校区描述');
                $table->unsignedBigInteger('last_updated_by')->default(0)->comment('最后更新该校区的信息的用户');
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('no action')->onUpdate('no action');
            });

            DB::statement(" ALTER TABLE campuses comment '校区表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campuses');
    }
}
