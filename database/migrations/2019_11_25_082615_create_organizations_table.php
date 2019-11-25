<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('关联的学校');
            $table->string('name',50)->comment('机构名称');
            $table->unsignedSmallInteger('level')->default(1)->comment('机构层级');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('上级机构, 0 表示是根');
            $table->string('phone')->nullable()->comment('联系电话');
            $table->string('address')->nullable()->comment('地址');
            $table->text('description')->nullable()->comment('机构职能介绍');
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
        Schema::dropIfExists('organizations');
    }
}
