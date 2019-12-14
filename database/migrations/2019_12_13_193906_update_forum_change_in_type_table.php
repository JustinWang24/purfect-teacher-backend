<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForumChangeInTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_types', function (Blueprint $table) {
            $table->unsignedBigInteger('type')->comment('属于 1:帖子, 2:社团');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
