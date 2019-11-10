<?php
/**
 * 构造正确的业务逻辑, 处理前端的快速用户定位请求
 */

namespace App\BusinessLogic\QuickSearch;
use App\BusinessLogic\QuickSearch\Contracts\IQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\CampusQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\DepartmentQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\EmployeeQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\GradeQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\InstituteQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\MajorQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\RegistrationFormsQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\StudentQuickSearchLogic;
use Illuminate\Http\Request;

class Factory
{
    /**
     * @param Request $request
     * @return IQuickSearchLogic|null
     */
    public static function GetInstance(Request $request){
        $instance = null;

        switch ($request->get('scope')){
            case 'campus':
                $instance = new CampusQuickSearchLogic($request);
                break;
            case 'institute':
                $instance = new InstituteQuickSearchLogic($request);
                break;
            case 'department':
                $instance = new DepartmentQuickSearchLogic($request);
                break;
            case 'major':
                $instance = new MajorQuickSearchLogic($request);
                break;
            case 'grade':
                $instance = new GradeQuickSearchLogic($request);
                break;
            case 'employee':
                $instance = new EmployeeQuickSearchLogic($request);
                break;
            case 'student':
                $instance = new StudentQuickSearchLogic($request);
                break;
            case 'registrations':
                $instance = new RegistrationFormsQuickSearchLogic($request);
                break;
            default:
                break;
        }

        return $instance;
    }
}