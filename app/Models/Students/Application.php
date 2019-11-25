<?php

namespace App\Models\Students;

use App\User;
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


    const STATUS_CHECK  = 0;
    const STATUS_PASS   = 1;
    const STATUS_REFUSE = 2;
    const STATUS_CHECK_TEXT  = '审核中';
    const STATUS_PASS_TEXT   = '已通过';
    const STATUS_REFUSE_TEXT = '已拒绝';




    /**
     * 申请类型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicationType() {
         return $this->belongsTo(ApplicationType::class);
    }


    public function user() {
        return $this->belongsTo(User::class);
    }


    /**
     * 全部状态
     * @return array
     */
    public function getAllStatus() {
        return [
            self::STATUS_CHECK => self::STATUS_CHECK_TEXT,
            self::STATUS_PASS  => self::STATUS_PASS_TEXT,
            self::STATUS_REFUSE=> self::STATUS_REFUSE_TEXT,
        ];
    }

    /**
     * 获取当前状态
     * @return mixed
     */
    public function getStatusTextAttribute() {
        $data = $this->getAllStatus();
        return $data["{$this->status}"];
    }



}
