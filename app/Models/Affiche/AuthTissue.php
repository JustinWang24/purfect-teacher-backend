<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AuthTissue extends Model
{
    protected $table = 'auth_tissues';
    protected $fillable = [
        'user_id',
        'school_id',
        'campus_id',
        'authu_number',
        'authu_name',
        'authu_cardno',
        'authu_tissusname',
        'authu_tissusdesc',
        'authu_refusedesc',
        'authu_checktime',
        'houtai_operateid',
        'houtai_operatename',
        'status',
        'created_at',
        'updated_at',
    ];
}






