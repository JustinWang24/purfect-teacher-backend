<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixErrorInDepartmentAdvisers1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('department_advisers', function (Blueprint $table) {
            $table->string('department_name',50)->change();
            $table->string('user_name',50)->change();
        });
        \App\Models\Schools\DepartmentAdviser::create([
            'school_id'=>1,
            'department_id'=>1,
            'department_name'=>'国际金融系',
            'user_id'=>11,
            'user_name'=>'王五',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_advisers', function (Blueprint $table) {
            //
        });
    }
}
