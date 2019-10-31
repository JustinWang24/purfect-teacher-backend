<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 11:08 PM
 */

namespace App\BusinessLogic\QuickSearch\Impl;

use App\BusinessLogic\QuickSearch\Contracts\IQuickSearchLogic;
use App\Dao\Users\GradeUserDao;
use Illuminate\Http\Request;

abstract class AbstractQuickSearchLogic implements IQuickSearchLogic
{
    protected $queryString;
    protected $scope;
    protected $schoolId;

    public function __construct(Request $request)
    {
        $this->schoolId = $request->get('school');
        $this->scope = $request->get('scope');
        $this->queryString = $request->get('query');
    }

    public function getUsers()
    {
        $dao = new GradeUserDao();
        return $dao->getUsersWithNameLike($this->queryString, $this->schoolId);
    }
}