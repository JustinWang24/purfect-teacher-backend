<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/11/19
 * Time: 12:04 AM
 */

namespace App\BusinessLogic\QuickSearch\Impl;


use App\Dao\Schools\MajorDao;
use Illuminate\Http\Request;

class MajorQuickSearchLogic extends AbstractQuickSearchLogic
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getFacilities()
    {
        $dao = new MajorDao();
        return $dao->searchByName($this->queryString, $this->schoolId);
    }
}
