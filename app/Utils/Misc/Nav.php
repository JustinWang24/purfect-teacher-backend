<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/11/19
 * Time: 9:27 AM
 */

namespace App\Utils\Misc;
use Illuminate\Support\Facades\Route;

class Nav
{
    public static function IsActive($current){
        return strpos(Route::current()->getName(), $current) !== false;
    }

    public static function IsBasicNav(){
        return strpos(Route::current()->getName(), 'school_manager.school.') !== false ||
            strpos(Route::current()->getName(), 'school_manager.major.') !== false ||
            strpos(Route::current()->getName(), 'school_manager.grade.') !== false ||
            strpos(Route::current()->getName(), 'school_manager.campus.') !== false ||
            strpos(Route::current()->getName(), 'school_manager.institute.') !== false ||
            strpos(Route::current()->getName(), 'school_manager.student.') !== false ||
            strpos(Route::current()->getName(), 'school_manager.department.') !== false;
    }
}