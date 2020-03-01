<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			if (!Schema::hasColumn('users', 'user_signture')) {
                $table->string('user_signture',300)->nullable()->comment('签名');
            }
			if (!Schema::hasColumn('users', 'user_fans_number')) {
				$table->unsignedMediumInteger('user_fans_number')->index()->default(0)->comment('粉丝数量');	
            }
			if (!Schema::hasColumn('users', 'user_focus_number')) {
				$table->unsignedMediumInteger('user_focus_number')->index()->default(0)->comment('关注数量');	
            }
			if (!Schema::hasColumn('users', 'user_praise_number')) {
				$table->unsignedMediumInteger('user_praise_number')->index()->default(0)->comment('点赞数量');	
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_signture');
            $table->dropColumn('user_fans_number');
            $table->dropColumn('user_focus_number');
            $table->dropColumn('user_praise_number');
        });
    }
}
