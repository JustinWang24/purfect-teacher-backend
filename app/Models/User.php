<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;

class User extends Authenticatable
{
    use Notifiable, HasRole;

    // 描述用户状态的常量
    const STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED = 1;
    const STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED_TEXT = '等待验证手机号';
    const STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED = 2;
    const STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED_TEXT = '等待验证身份证';
    const STATUS_VERIFIED = 3;
    const STATUS_VERIFIED_TEXT = '身份已验证';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password','mobile_verified_at','mobile','uuid','status','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mobile_verified_at' => 'datetime',
    ];
}
