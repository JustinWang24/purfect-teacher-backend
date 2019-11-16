<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/11/19
 * Time: 8:21 PM
 */

namespace App\BusinessLogic\HomePage\Impl;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\User;
use Illuminate\Http\Request;

class TeacherHomepageLogic implements IHomePageLogic
{
    /**
     * @var User $teacher
     */
    private $teacher = null;
    private $data = [];
    public $request;

    public function __construct(Request $request)
    {
        $this->teacher = $request->user();
        $this->request = $request;
    }

    /**
     * 获取教师首页需要加载的数据
     *
     * @return array
     */
    public function getDataForView()
    {
        $this->data['needChart'] = true;
        $this->data['pageTitle'] = '我的首页';
        $this->data['teacher'] = $this->teacher;
        // Todo: 加载老师profile

        // Todo: 加载老师所负责的班级
        $this->data['gradeUser'] = $this->teacher->gradeUser;

        // Todo: 加载老师今天的课程

        // Todo: 加载系统发给老师的消息

        // Todo: 加载老师提交的任何申请

        // Todo: 加载老师所教授的课程

        return $this->data;
    }
}