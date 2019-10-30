<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 30/10/19
 * Time: 10:41 PM
 */

namespace App\Models\Schools;
use App\Utils\Misc\ConfigurationTool;

trait HasConfigurations
{

    public function getOptionalCoursesPerYear(){
        $field = ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR;
        return $this->$field ?? 1;
    }

    public function isSelfStudyNeedRegistration(){
        $field = ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION;
        return $this->$field;
    }

    public function getStudyWeeksPerTerm(){
        $field = ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM;
        return $this->$field ?? ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM;
    }
}