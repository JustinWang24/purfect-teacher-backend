<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 11:34 PM
 */

namespace App\BusinessLogic\QuickSearch\Impl;
use App\Dao\Schools\CampusDao;
use Illuminate\Http\Request;

class CampusQuickSearchLogic extends AbstractQuickSearchLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getFacilities()
    {
        $dao = new CampusDao();
        return $dao->searchByName($this->queryString, $this->schoolId);
    }

    public function getNextAction($facility)
    {
        return route('school_manager.campus.institutes',['by'=>'campus','uuid'=>$facility->id]);
    }
}