<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('学校的 ID');
            $table->unsignedBigInteger('user_id')->comment('用户的 ID');
            $table->unsignedBigInteger('organization_id')->comment('用户所在的机构 ID');
            $table->unsignedSmallInteger('title_id')
                ->default(\App\Utils\Misc\Contracts\Title::MEMBER)
                ->comment('用户的 title ID');

            $table->string('title',50)->comment('用户的 title 的名字');
            $table->string('name',50)->comment('用户的名字');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_organizations');
    }
}
