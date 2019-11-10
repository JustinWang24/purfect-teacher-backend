<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/11/19
 * Time: 12:00 AM
 */

namespace App\BusinessLogic\QuickSearch\Impl;

use App\Dao\Schools\DepartmentDao;
use Illuminate\Http\Request;

class DepartmentQuickSearchLogic extends AbstractQuickSearchLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getFacilities()
    {
        $dao = new DepartmentDao();

        return $dao->searchByName($this->queryString, $this->schoolId);
    }

    public function getNextAction($facility)
    {
        return route('school_manager.department.majors',['by'=>'department','uuid'=>$facility->id]);
    }
}