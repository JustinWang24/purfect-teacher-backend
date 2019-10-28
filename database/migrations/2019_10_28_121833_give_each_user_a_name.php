<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class GiveEachUserAName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $counts = DB::table('users')->count();

        $faker = Factory::create();

        foreach (range(1,$counts) as $userId){
            DB::table('users')->where('id',$userId)->update(['name'=>$faker->name]);
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
