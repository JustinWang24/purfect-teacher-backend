<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\EnrolmentStep\EnrolmentStep;

class PutSomeGeneralStepsInWelcome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            '欢迎语','交学费','领生活用品','入学绿色通道'
        ];
        foreach ($data as $datum) {
            $step = EnrolmentStep::where('name',$datum)->first();
            if(!$step){
                EnrolmentStep::create(['name'=>$datum]);
            }
        }
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
