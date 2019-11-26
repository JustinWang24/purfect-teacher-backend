<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('public')->default(true)->comment(
                '是否需要登录才可看到'
            );
            $table->unsignedInteger('clicks')->default(0)->comment('点击数');
            $table->unsignedSmallInteger('type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('public');
            $table->dropColumn('clicks');
        });
    }
}
