<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use App\User;
use App\Models\School;
use App\Models\Users\GradeUser;
use Illuminate\Database\Eloquent\Model;

class CollegeGroup extends Model
{
    protected $table = 'college_groups';
    protected $fillable = [
        'groupid',
        'user_id',
        'school_id',
        'campus_id',
        'group_typeid',
        'group_pics',
        'group_title',
        'group_number',
        'authu_refusedesc',
        'group_content',
        'group_time1',
        'status',
        'created_at',
        'updated_at',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school() {
        return $this->belongsTo(School::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gradeUser() {
        return $this->belongsTo(GradeUser::class, 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collegeGroupPics() {
        return $this->hasMany(CollegeGroupPics::class, 'group_id', 'groupid');
    }
}
