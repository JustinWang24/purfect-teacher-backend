<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use App\Models\Students\StudentProfile;
use App\Models\Users\GradeUser;
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

    public function studentProfile(){
        return $this->belongsTo(StudentProfile::class, 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gradeUser() {
        return $this->belongsTo(GradeUser::class, 'user_id', 'user_id');
    }
}
