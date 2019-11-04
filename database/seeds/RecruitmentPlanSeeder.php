<?php

use Illuminate\Database\Seeder;
use App\Models\Schools\RecruitmentPlan;

class RecruitmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       factory(RecruitmentPlan::class, 10)->create()->each(function ($plan) {
            $plan->save();
        });
    }
}
