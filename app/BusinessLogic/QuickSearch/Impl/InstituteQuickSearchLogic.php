<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 11:45 PM
 */

namespace App\BusinessLogic\QuickSearch\Impl;


use App\Dao\Schools\InstituteDao;
use Illuminate\Http\Request;

class InstituteQuickSearchLogic extends AbstractQuickSearchLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getFacilities()
    {
        $dao = new InstituteDao();
        return $dao->searchByName($this->queryString, $this->schoolId);
    }
}