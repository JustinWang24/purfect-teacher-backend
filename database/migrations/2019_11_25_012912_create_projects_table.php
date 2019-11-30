<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\OA\Project;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('发起人所属的学校');
            $table->unsignedBigInteger('user_id')->comment('发起人');
            $table->string('title',200)->comment('项目标题');
            $table->text('content')->nullable()->comment('项目内容');
            $table->unsignedSmallInteger('status')->default(Project::STATUS_IN_PROGRESS)
                ->comment('项目状态');
            $table->timestamps();
        });
        Schema::create('oa_project_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('参与成员的 id');
            $table->unsignedBigInteger('project_id')->comment('关联的项目 ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_projects');
        Schema::dropIfExists('oa_project_members');
    }
}
