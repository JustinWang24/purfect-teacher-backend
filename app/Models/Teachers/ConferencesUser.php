<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/24
 * Time: 上午9:41
 */
namespace App\Models\Teachers;
use Illuminate\Database\Eloquent\Model;

class ConferencesUser extends Model
{
    protected $fillable = [
        'conference_id','user_id','school_id','status','from','to','begin','end',
    ];


}