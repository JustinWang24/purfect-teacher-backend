<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecruitmentIntroInSchoolConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->text('recruitment_intro')->nullable()->comment('招生简章');
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
            $table->dropColumn('recruitment_intro');
        });
    }
}
