<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 11:49 PM
 */

namespace App\BusinessLogic\UsersListPage\Impl;

use Illuminate\Http\Request;
use App\Dao\Users\GradeUserDao;
use App\User;
use App\Models\Acl\Role;

class RegisteredStudentsListLogic extends AbstractDataListLogic
{
    /**
     * RegisteredStudentsListLogic constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getUsers()
    {
        $result = [];
        $dao = new GradeUserDao($this->request->user());
        $studentTypes = Role::GetStudentUserTypes();
        switch ($this->request->get('by')){
            case 'campus':
                $result = $dao->paginateUserByCampus($this->id, $studentTypes);
                break;
            case 'institute':
                $result = $dao->paginateUserByInstitute($this->id, $studentTypes);
                break;
            case 'department':
                $result = $dao->paginateUserByDepartment($this->id, $studentTypes);
                break;
            case 'major':
                $result = $dao->paginateUserByMajor($this->id, $studentTypes);
                break;
            case 'grade':
                $result = $dao->paginateUserByGrade($this->id, $studentTypes);
                break;
            default:
                break;
        }
        return ['students'=>$result];
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
