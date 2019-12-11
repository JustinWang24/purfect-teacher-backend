<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 19/10/19
 * Time: 12:22 AM
 */

namespace App\BusinessLogic\UsersListPage\Impl;
use Illuminate\Http\Request;
use App\Dao\Users\GradeUserDao;
use App\Models\Acl\Role;

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
        $employeeTypes = Role::GetTeacherUserTypes();
        switch ($this->request->get('by')){
            case 'campus':
                $result = $dao->paginateUserByCampus($this->id, $employeeTypes);
                break;
            case 'institute':
                $result = $dao->paginateUserByInstitute($this->id, $employeeTypes);
                break;
            case 'department':
                $result = $dao->paginateUserByDepartment($this->id, $employeeTypes);
                break;
            case 'major':
                $result = $dao->paginateUserByMajor($this->id, $employeeTypes);
                break;
            default:
                break;
        }
        return ['employees'=>$result];
    }

    public function getViewPath()
    {
        return 'teacher.users.teachers';
    }

    public function getData()
    {
        return $this->getUsers();
    }
}