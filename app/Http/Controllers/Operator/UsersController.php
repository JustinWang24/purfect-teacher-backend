<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 9:12 PM
 */

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\User;
use App\Dao\Users\GradeUserDao;
use App\Dao\Schools\CampusDao;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users(MyStandardRequest $request){
        $forType = intval($request->get('type'));

        $viewPath = 'teacher.users.teachers';
        $dao = new GradeUserDao();
        $campusDao = new CampusDao($request->user());
        $this->dataForView['campus'] = $campusDao->getCampusById($request->uuid());
        $this->dataForView['returnPath'] = route('school_manager.school.view');
        $this->dataForView['appendedParams'] = [
            'type'=>$forType,'uuid'=>$request->uuid()
        ];

        if($forType === User::TYPE_STUDENT){
            // 查看学生
            $viewPath = 'teacher.users.students';
            $this->dataForView['students'] = $dao->paginateUserByCampus($request->uuid(), User::TYPE_STUDENT);
        }
        elseif ($forType === User::TYPE_EMPLOYEE){
            // 查看教职工
            $this->dataForView['employees'] = $dao->paginateUserByCampus($request->uuid(), User::TYPE_EMPLOYEE);
        }

        return view($viewPath, $this->dataForView);
    }
}