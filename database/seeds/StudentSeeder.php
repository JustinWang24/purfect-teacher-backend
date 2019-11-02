<?php

use App\Models\Students\StudentProfile;
use Illuminate\Database\Seeder;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 50)->create()->each(function ($user) {
            $user->save();
            $profile = factory(StudentProfile::class)->make();
            $profile->user_id = $user->id;
            $profile->uuid = $user->uuid;
            $profile->save();
            $res = factory(RegistrationInformatics::class)->make();
            $res->user_id = $user->id;
            $res->name = $user->name;
            $res->save();
        });
    }
}
