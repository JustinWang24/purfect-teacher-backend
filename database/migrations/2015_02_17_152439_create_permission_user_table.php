<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePermissionUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permission_user', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('permission_id')->unsigned()->index()->references('id')->on('permissions')->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index()->references('id')->on('users')->onDelete('cascade');
			$table->timestamps();
		});

        DB::statement(" ALTER TABLE permission_user comment '权限-用户 关系表' ");

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permission_user');
	}

}
