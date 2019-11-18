<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 2:14 PM
 */

namespace App\BusinessLogic\HomePage;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\BusinessLogic\HomePage\Impl\OperatorHomePageLogic;
use App\BusinessLogic\HomePage\Impl\SchoolManagerHomePageLogic;
use App\BusinessLogic\HomePage\Impl\SuHomePageLogic;
use App\BusinessLogic\HomePage\Impl\TeacherHomepageLogic;
use App\User;
use Illuminate\Http\Request;

class Factory
{
    public static function GetLogic(Request $request){
        /**
         * @var IHomePageLogic $instance
         */
        $instance = null;
        /**
         * @var User $user
         */
        $user = $request->user();

        if($user->isSuperAdmin()){
            $instance = new SuHomePageLogic($request);
        }elseif($user->isOperatorOrAbove()){
            $instance = new OperatorHomePageLogic($request);
        }
        elseif ($user->isSchoolAdminOrAbove()){
            $instance = new SchoolManagerHomePageLogic($request);
        }
        elseif ($user->isTeacher()){
            $instance = new TeacherHomepageLogic($request);
        }

        return $instance;
    }
}
