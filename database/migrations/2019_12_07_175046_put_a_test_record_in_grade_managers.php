<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PutATestRecordInGradeManagers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_managers', function (Blueprint $table) {
            $table->string('adviser_name',30)->nullable()->change();
            $table->string('monitor_name',30)->nullable()->change();
            $table->dropColumn('school');
            $table->unsignedBigInteger('school_id');
        });

        if (env('APP_DEBUG', false)){
//            \App\Models\Schools\GradeManager::create([
//                'school_id'=>1,
//                'grade_id'=>1,
//                'adviser_id'=>10,
//                'adviser_name'=>\App\User::find(10)->name,
//                'monitor_id'=>6,
//                'monitor_name'=>\App\User::find(6)->name,
//            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_managers', function (Blueprint $table) {
            $table->dropColumn('adviser_name');
            $table->dropColumn('monitor_name');
            $table->dropColumn('school_id');
            $table->unsignedBigInteger('school');
        });
    }
}
