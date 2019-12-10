<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/12/19
 * Time: 4:11 PM
 */

namespace App\BusinessLogic\HomePage\Impl;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\Utils\Pipeline\IFlow;
use Illuminate\Http\Request;
use App\User;
use App\Dao\Pipeline\FlowDao;

class StudentHomePageLogic implements IHomePageLogic
{
    /**
     * @var User $teacher
     */
    private $student = null;
    private $data = [];
    public $request;

    public function __construct(Request $request)
    {
        $this->student = $request->user();
        $this->request = $request;
    }

    public function getDataForView()
    {
        $this->data['pageTitle'] = '我的首页';
        $this->data['student'] = $this->student;
        // 加载学生的profile
        $this->data['profile'] = $this->student->profile;

        // 加载班级
        $this->data['gradeUser'] = $this->student->gradeUser;

        // Todo: 加载学生今天的课程

        // Todo: 加载系统发给学生的消息

        // Todo: 加载学生可用的申请
        $flowDao = new FlowDao();
        $this->data['groupedFlows'] = $flowDao->getGroupedFlows(
            $this->request->session()->get('school.id'), [IFlow::TYPE_STUDENT_ONLY]);

        return $this->data;
    }
}