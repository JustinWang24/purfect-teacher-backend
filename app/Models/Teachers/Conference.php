<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午4:05
 */
namespace App\Models\Teachers;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;
class Conference extends Model
{
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id','id');
    }


    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}