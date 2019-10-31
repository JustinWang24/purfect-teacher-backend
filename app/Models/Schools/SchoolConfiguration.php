<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Misc\ConfigurationTool;

class SchoolConfiguration extends Model
{
    use HasConfigurations;

    protected $fillable = [
        ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM,
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION,
        ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR,
        'school_id'
    ];

    public $casts = [
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION => 'boolean'
    ];

    /**
     * 为学校创建默认的配置项
     * @param $school
     * @return SchoolConfiguration
     */
    public function createDefaultConfig($school){
        $data = [
            ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM => 20,
            ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION => false,
            ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR=>1,
            'school_id'=>$school->id ?? $school
        ];
        return self::create($data);
    }
}
