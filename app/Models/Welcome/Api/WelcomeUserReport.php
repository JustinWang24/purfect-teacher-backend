<?php
namespace App\Models\Welcome\Api;

use App\Models\School;
use App\Models\Users\GradeUser;
use App\Models\Students\StudentProfile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WelcomeUserReport extends Model
{
    protected $fillable = [
        'user_id',
        'flow_id',
        'school_id',
        'campus_id',
        'steps_1_str',
        'steps_1_date',
        'steps_2_str',
        'steps_2_date',
        'complete_date',
        'status',
        'created_at',
        'updated_at'
    ];
}
