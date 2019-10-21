<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 19/10/19
 * Time: 12:22 AM
 */

namespace App\BusinessLogic\UsersListPage\Impl;
use Illuminate\Http\Request;
use App\User;
use App\Dao\Users\GradeUserDao;

class TeachersListLogic extends AbstractDataListLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getUsers()
    {
        $result = [];
        $dao = new GradeUserDao($this->request->user());
        switch ($this->request->get('by')){
            case 'campus':
                $result = $dao->paginateUserByCampus($this->id, User::TYPE_EMPLOYEE);
                break;
            case 'institute':
                $result = $dao->paginateUserByInstitute($this->id, User::TYPE_EMPLOYEE);
                break;
            default:
                break;
        }
        return ['employees'=>$result];
    }

    public function getViewPath()
    {
        return 'teacher.users.students';
    }

    public function getData()
    {
        return $this->getUsers();
    }
}