<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyImportTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_task', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable(true)->comment('学校id');
        });
        Schema::table('import_log', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable(true)->comment('学校id');
            $table->string('only_flag',32)->nullable(true)->comment('唯一标识，用于相同记录的标记');
            $table->index('only_flag', 'onlyflag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_task', function (Blueprint $table) {
            if(Schema::hasColumn('import_task','school_id')){
                $table->dropColumn('school_id');
            }
        });
        Schema::table('import_log', function (Blueprint $table) {
            if(Schema::hasColumn('import_log','school_id')){
                $table->dropColumn('school_id');
            }
            if(Schema::hasColumn('import_log','only_flag')){
                $table->dropColumn('only_flag');
            }
        });
    }
}
