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

class RegisteredStudentsListLogic extends AbstractUsersListLogic
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
        $dao = new GradeUserDao();
        switch ($this->request->get('by')){
            case 'campus':
                $result = $dao->paginateUserByCampus($this->id, User::TYPE_STUDENT);
                break;
            case 'institute':
                $result = $dao->paginateUserByInstitute($this->id, User::TYPE_STUDENT);
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
}