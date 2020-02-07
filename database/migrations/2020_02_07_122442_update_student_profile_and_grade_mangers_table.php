<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStudentProfileAndGradeMangersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('contact_number',20)->nullable()->comment('联系电话');
       });

       Schema::table('grade_managers', function (Blueprint $table) {
            $table->string('group_id')->nullable()->comment('团支书ID');
            $table->string('group_name')->nullable()->comment('团支书姓名');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('contact_number');
        });

        Schema::table('grade_managers', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropColumn('group_name');
        });
    }
}
