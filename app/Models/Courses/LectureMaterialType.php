<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/18
 * Time: 下午3:07
 */

namespace App\Models\Courses;


use Illuminate\Database\Eloquent\Model;

class LectureMaterialType extends Model
{
    protected $fillable = ['school_id', 'name'];

}