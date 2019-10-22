<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Dao\Schools\SchoolResourceDao;
use App\Dao\Teachers\TeacherProfileDao;

class SchoolSceneryController extends Controller
{

    /**
     * 校园相册
     */
    public  function  index()
    {

        $schoolResourceDao = new SchoolResourceDao;
        $teacherDao        = new TeacherProfileDao;

        $teacherInfo = $teacherDao->getTeacherProfileByTeacherIdOrUuid(Auth::id());
        $schoolImg   = $schoolResourceDao->getSchoolImgBySchoolIdOrUuId($teacherInfo->getTeacherSchoolId());
        $schoolVideo = $schoolResourceDao->getSchoolVideoBySchoolIdOrUuId($teacherInfo->getTeacherSchoolId());

        if ($schoolVideo) {
            $this->dataForView['video'] = $schoolVideo->toArray();
        } else {
            $this->dataForView['video'] = [];
        }
        if ($schoolImg) {
            $this->dataForView['img'] = $schoolImg->toArray();
        } else {
            $this->dataForView['img'] = [];
        }

        $this->dataForView['pageTitle'] = '校园相册';
        return view('Teacher.SchoolScenery.index', $this->dataForView);
    }


    /**
     * 学校简介
     */
    public  function profile()
    {
        $this->dataForView['pageTitle'] = '学校简介';
        return view('Teacher.SchoolScenery.profile', $this->dataForView);
    }



}
