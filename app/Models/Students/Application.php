<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Application
 * @property integer $id
 * @property integer $user_id
 * @property integer $school_id
 * @property integer $grade_id
 * @property integer $application_type_id
 * @property string  $reason
 * @property integer  $census
 * @property integer  $family_population
 * @property integer  $general_income
 * @property integer  $per_capita_income
 * @property string  $income_source
 * @property integer  $status
 * @property integer  $last_update_by
 * @package App\Models\Students
 */
class Application extends Model
{

    protected $fillable = [
        'user_id', 'school_id', 'grade_id', 'application_type_id', 'reason',
        'census', 'family_population', 'general_income', 'per_capita_income',
        'income_source', 'status', 'last_update_by'
    ];


    /**
     * 申请类型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicationType() {
         return $this->belongsTo(ApplicationType::class);
    }
}
