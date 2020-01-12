<?php
namespace App\Models\Welcome\Api;

use App\Models\School;
use App\Models\Users\GradeUser;
use App\Models\Students\StudentProfile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WelcomeConfig extends Model
{
    /**
     * 关联的学校
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoola(){
        return $this->belongsTo(School::class);
    }

}
