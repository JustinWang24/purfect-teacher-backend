<?php

use App\Models\Schools\School;
use Illuminate\Database\Migrations\Migration;
use App\Dao\Schools\SchoolDao;
use Ramsey\Uuid\Uuid;

class CreateSchoolData extends Migration
{
    /**
     * 创建学校 学校资源
     */
    public function up()
    {
        $su = [
                'uuid'=>Uuid::uuid4()->toString(),
                'max_students_number'=> '100',
                'max_employees_number'=>'100',
                'name'=> 'TEST北京大学',
        ];

        School::create($su);
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
