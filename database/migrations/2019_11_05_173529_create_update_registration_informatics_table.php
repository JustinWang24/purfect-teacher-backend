<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateRegistrationInformaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registration_informatics', function (Blueprint $table) {
            $table->unsignedSmallInteger('status')
                ->default(\App\Models\RecruitStudent\RegistrationInformatics::WAITING)
                ->comment('状态 1待审核 2报名审核被拒绝 3报名审核已通过 4被拒绝录取 5被录取 6已报到')
                ->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
