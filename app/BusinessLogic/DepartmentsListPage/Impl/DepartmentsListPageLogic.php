<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 7:53 AM
 */

namespace App\BusinessLogic\DepartmentsListPage\Impl;

use App\BusinessLogic\UsersListPage\Impl\AbstractDataListLogic;
use App\Dao\Schools\DepartmentDao;
use Illuminate\Http\Request;

class DepartmentsListPageLogic extends AbstractDataListLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getData()
    {
        $dao = new DepartmentDao($this->request->user());
        return $dao->getByInstitute($this->request->get('uuid'));
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsers()
    {
        return [];
    }

    public function getViewPath()
    {
        return 'school_manager.school.departments';
    }
}