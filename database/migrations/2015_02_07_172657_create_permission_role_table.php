<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePermissionRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE permission_role comment '权限-角色 关系表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_role');
    }

}
