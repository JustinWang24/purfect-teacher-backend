<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolIntroInSchoolConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->text('campus_intro')->nullable()->comment('学校的校园简介');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->dropColumn('campus_intro');
        });
    }
}
