<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_follow', function (Blueprint $table) {
            $table->unique(['user_id', 'to_user_id'], 'idx_user_to_user');
        });
        Schema::table('social_followed', function (Blueprint $table) {
            $table->unique(['user_id', 'from_user_id'], 'idx_user_to_fromuser');
        });
        Schema::table('social_like', function (Blueprint $table) {
            $table->unique(['user_id', 'to_user_id'], 'idx_user_to_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_follow', function (Blueprint $table) {
            $table->dropUnique('idx_user_to_user');
        });
        Schema::table('social_followed', function (Blueprint $table) {
            $table->dropUnique('idx_user_to_fromuser');
        });
        Schema::table('social_like', function (Blueprint $table) {
            $table->dropUnique('idx_user_to_user');
        });
    }
}
