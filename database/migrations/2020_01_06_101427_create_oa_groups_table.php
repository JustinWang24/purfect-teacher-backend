<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('user_id')->comment('创建人ID');
            $table->string('name');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_groups comment '分组表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_groups');
    }
}
