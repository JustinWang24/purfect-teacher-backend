<?php

namespace App\Models;

use App\Dao\Timetable\TimeSlotDao;
use App\Models\Acl\Role;
use App\Models\Schools\SchoolConfiguration;
use App\Models\Teachers\Performance\TeacherPerformanceConfig;
use App\Models\Users\GradeUser;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Schools\Campus;
use Illuminate\Http\Request;
use App\Models\Timetable\TimeSlot;

class School extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uuid','max_students_number','max_employees_number','name',
        'state', // 哪个省
        'level', // 学校批次名称: 高职
        'category_code_state', // 省市科类代码:
        'category_code', // 学校科类代码: 01
        'category_name', // 学校科类名称: 理工
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastUpdatedBy(){
        return $this->belongsTo(User::class,'last_updated_by');
    }

    /**
     * 学校包含的校区
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campuses(){
        return $this->hasMany(Campus::class)->orderBy('name','asc');
    }

    /**
     * 学校预制的时间段
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeFrame(){
        return $this->hasMany(TimeSlot::class)->select(['id','name','type','from','to','season'])
            ->orderBy('from','asc');
    }


    public function getCurrentTimeFrame(){
        /**
         * @var SchoolConfiguration $config
         */
        $config = $this->configuration;
        $seasonType = GradeAndYearUtil::GetCurrentSeason($config);
        $slots = TimeSlot::select(['id','name','type','from','to','season'])
            ->where('season',$seasonType)
            ->orderBy('from','asc')->get();

        if(count($slots)===0){
            // 还没有创建
            $dao = new TimeSlotDao();
            $frames = $dao->getDefaultTimeFrame(TimeSlot::SEASONS_WINTER_AND_SPRINT)['frames'];
            foreach ($frames as $frame) {
                $frame['school_id'] = 1;
                $frame['season'] = TimeSlot::SEASONS_WINTER_AND_SPRINT;
                $dao->createTimeSlot($frame);
            }
            $frames = $dao->getDefaultTimeFrame(TimeSlot::SEASONS_SUMMER_AND_AUTUMN)['frames'];
            foreach ($frames as $frame) {
                $frame['school_id'] = 1;
                $frame['season'] = TimeSlot::SEASONS_SUMMER_AND_AUTUMN;
                $dao->createTimeSlot($frame);
            }
            $slots = TimeSlot::select(['id','name','type','from','to','season'])->where('season',$seasonType)->orderBy('from','asc')->get();
        }

        return $slots;
    }

    /**
     * 学校的配置
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function configuration(){
        return $this->hasOne(SchoolConfiguration::class);
    }

    /**
     * 将学校信息保存在 session 中
     * @param Request $request
     */
    public function savedInSession(Request $request){
        $request->session()->put('school.id',$this->id);
        $request->session()->put('school.uuid',$this->uuid);
        $request->session()->put('school.name',$this->name);
    }

    /**
     * 学校的管理员账户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schoolManagers(){
        return $this->hasMany(GradeUser::class)->where('user_type',Role::SCHOOL_MANAGER);
    }

    public function teacherPerformanceConfigs(){
        return $this->hasMany(TeacherPerformanceConfig::class);
    }

    /**
     * Logo 的变形, 返回全 URL 网址
     * @param $value
     * @return string
     */
    public function getLogoAttribute($value){
        return asset($value);
    }
}
