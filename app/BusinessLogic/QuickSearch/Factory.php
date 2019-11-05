<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 11:03 PM
 */

namespace App\BusinessLogic\QuickSearch;
use App\BusinessLogic\QuickSearch\Contracts\IQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\CampusQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\EmployeeQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\InstituteQuickSearchLogic;
use App\BusinessLogic\QuickSearch\Impl\MajorQuickSearchLogic;
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
                $instance = new InstituteQuickSearchLogic($request);
                break;
            case 'major':
                $instance = new MajorQuickSearchLogic($request);
                break;
            case 'grade':
                $instance = new MajorQuickSearchLogic($request);
                break;
            case 'employee':
                $instance = new EmployeeQuickSearchLogic($request);
                break;
            default:
                break;
        }

        return $instance;
    }
}