<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Users\GradeUser;

class AddNameIntoGradeUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_users', function (Blueprint $table) {
             $table->string('name',40)
                ->comment('用户的名字: 用来根据名字搜索用')->after('user_id');
        });

        $total = DB::table('users')->count();

        foreach (range(1, $total) as $userId) {
            $user = DB::table('users')->select('name')->where('id',$userId)->first();
	    if($user){
	    	$gu = GradeUser::select(['name','id'])
                ->where('user_id',$userId)
		->first();
		if($gu){
			$gu->name = $user->name;
			$gu->save();
		}
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
        //
    }
}
