<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class CollegeGroupJoin extends Model
{
    protected $table = 'college_group_joins';
    protected $fillable = [
        'group_id',
        'user_id',
        'school_id',
        'campus_id',
        'join_typeid',
        'join_userid',
        'join_adminid',
        'join_apply_desc1',
        'join_apply_desc2',
        'status',
        'created_at',
        'updated_at',
    ];
}
