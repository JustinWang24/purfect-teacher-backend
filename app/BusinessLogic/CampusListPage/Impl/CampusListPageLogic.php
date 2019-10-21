<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 9:02 AM
 */

namespace App\BusinessLogic\CampusListPage\Impl;


use App\BusinessLogic\UsersListPage\Impl\AbstractDataListLogic;
use App\Dao\Schools\MajorDao;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CampusListPageLogic extends AbstractDataListLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getData()
    {
        $dao = new MajorDao($this->request->user());
        return $dao->getByDepartment($this->id);
    }

    public function getUsers()
    {
        return [];
    }

    public function getViewPath()
    {
        return 'school_manager.school.majors';
    }
}