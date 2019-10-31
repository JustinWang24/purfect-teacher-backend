<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/11/19
 * Time: 12:04 AM
 */

namespace App\BusinessLogic\QuickSearch\Impl;


use App\Dao\Schools\GradeDao;
use Illuminate\Http\Request;

class GradeQuickSearchLogic extends AbstractQuickSearchLogic
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getFacilities()
    {
        $dao = new GradeDao();
        return $dao->searchByName($this->queryString, $this->schoolId);
    }
}
