<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class CollegeGroupNoticeReader extends Model
{
    protected $table = 'college_group_notice_readers';
    protected $fillable = [
        'user_id',
        'group_id',
        'notice_id',
        'school_id',
        'campus_id',
        'notice_apply',
        'status',
        'created_at',
        'updated_at',
    ];
}
