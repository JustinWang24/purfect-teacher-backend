<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/30
 * Time: 下午7:10
 */

namespace App\Models\Students;


use Illuminate\Database\Eloquent\Model;

class StudentLeave extends Model
{
    protected $fillable = [
        'school_id', 'grade_id', 'user_id','start_time', 'end_time'
    ];

    

}