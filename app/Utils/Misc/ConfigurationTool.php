<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 30/10/19
 * Time: 5:01 PM
 */

namespace App\Utils\Misc;


class ConfigurationTool
{
    const KEY_OPTIONAL_COURSES_PER_YEAR    = 'optional_courses_per_year';       // 表示学生每年可以选择的选修课数量的字段名
    const KEY_SELF_STUDY_NEED_REGISTRATION = 'self_study_need_registration';    // 表示学生上自习课, 是否需要签到
    const KEY_STUDY_WEEKS_PER_TERM         = 'study_weeks_per_term';            // 表示学生每学期的教学周数
    const DEFAULT_STUDY_WEEKS_PER_TERM     = 20;                                // 缺省的每学期学习周数: 20周

    const DEFAULT_PAGE_SIZE                = 20;                                // 缺省的数据库查询分页数
    const DEFAULT_PAGE_SIZE_QUICK_SEARCH   = 12;                                // 缺省的快速查询分页数
}