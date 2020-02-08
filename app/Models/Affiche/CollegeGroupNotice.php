<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class CollegeGroupNotice extends Model
{
    protected $table = 'college_group_notices';
    protected $fillable = [
        'user_id',
        'group_id',
        'school_id',
        'campus_id',
        'notice_content',
        'notice_number1',
        'notice_number2',
        'status',
        'created_at',
        'updated_at',
    ];
}
