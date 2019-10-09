<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\Acl\Role;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateSuperAdminAndOperators extends Migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        // 创建系统超级管理员用户
        $su = [
            'mobile'=>'18601216091',
            'uuid'=>Uuid::uuid4()->toString(),
            'password'=>Hash::make('BiDZ+L0IM6gTmbcReo='),
            'status'=>User::STATUS_VERIFIED,
            'type'=>Role::SUPER_ADMIN,
            'mobile_verified_at'=>Carbon::now(),
        ];
        User::create($su);

        $operator = [
            'mobile'=>'18510209803',
            'uuid'=>Uuid::uuid4()->toString(),
            'password'=>Hash::make('VtPY7zXMe012f'),
            'status'=>User::STATUS_VERIFIED,
            'type'=>Role::OPERATOR,
            'mobile_verified_at'=>Carbon::now(),
        ];
        User::create($operator);
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
