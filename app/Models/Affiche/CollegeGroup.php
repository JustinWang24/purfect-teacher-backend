<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class CollegeGroup extends Model
{
    protected $table = 'college_groups';
    protected $fillable = [
        'user_id',
        'school_id',
        'campus_id',
        'group_typeid',
        'group_pics',
        'group_title',
        'group_number',
        'group_content',
        'group_time1',
        'status',
        'created_at',
        'updated_at',
    ];
}
