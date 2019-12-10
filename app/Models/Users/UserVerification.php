<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    const PURPOSE_0 = 0;

    const PURPOSE_1 = 1;

    const PURPOSE_2 = 2;

    const PURPOSE_0_TEXT = '注册';

    const PURPOSE_1_TEXT = '登录';

    const PURPOSE_2_TEXT = '忘记密码';

    protected $fillable = [
        'user_id','code','created_at','purpose','mobile'
    ];
    public $timestamps = false;
}
