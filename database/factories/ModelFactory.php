<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Students\StudentProfile;
use App\User;
use App\Models\RecruitStudent\RegistrationInformatics;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Acl\Role;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'mobile'=>'3333'.rand(1, 99).rand(1, 99),
        'name' => $faker->name,
        'uuid'=>Uuid::uuid4()->toString(),
        'password'=>Hash::make('ac59075b964b0715'),
        'status'=>Role::VISITOR,
        'type'=>Role::VISITOR,
        'mobile_verified_at'=>Carbon::now(),
    ];
});

$factory->define(StudentProfile::class, function (Faker $faker) {
    return [
        'serial_number' => '0',
        'avatar' => 'www.xx.test',
        'device' => 'ios',
        'year' => '2019',
        'gender' => rand(1, 2),
        'country' => '北京',
        'state' => '北京',
        'city' => '北京市',
        'area' => '朝阳区',
        'address_line' => '电信工程局9楼',
        'id_number' => '2019' . rand(100, 300) . rand(1000, 3000) . rand(3999, 9000),
        'birthday' => '2019-11-1',
        'political_name' => '党员',
        'nation_name' => '叶赫那拉氏',
        'source_place' => '北京',
        'parent_name' => '帕菲特',
        'parent_mobile' => '9999' . rand(1, 99) . rand(1, 99)
    ];
});

$factory->define(RegistrationInformatics::class, function (Faker $faker) {
    return [
        'school_id' => rand(1, 9),
        'major_id' => rand(1, 3),
        'relocation_allowed' => rand(1, 2),
        'status' => rand(1, 3),
    ];
});
