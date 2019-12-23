<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/12/19
 * Time: 3:00 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers;

use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\BusinessLogic\OrganizationTitleHelpers\Impl\ClassAdviser;
use App\BusinessLogic\OrganizationTitleHelpers\Impl\DepartmentManager;
use App\BusinessLogic\OrganizationTitleHelpers\Impl\GradeAdviser;
use App\BusinessLogic\OrganizationTitleHelpers\Impl\SchoolCoordinator;
use App\BusinessLogic\OrganizationTitleHelpers\Impl\SchoolDeputy;
use App\BusinessLogic\OrganizationTitleHelpers\Impl\SchoolPrincipal;
use App\Utils\Misc\Contracts\Title;
use App\Utils\Pipeline\IUser;

class TitleToUsersFactory
{
    /**
     * @param $titleId
     * @param IUser $user
     * @return TitleToUsers
     */
    public static function GetInstance($titleId, IUser $user){
        $instance = null;
        switch ($titleId){
            case Title::SCHOOL_PRINCIPAL: // 校长
                $instance = new SchoolPrincipal($user->getSchoolId());
                break;
            case Title::SCHOOL_DEPUTY: // 副校长
                $instance = new SchoolDeputy($user->getSchoolId());
                break;
            case Title::SCHOOL_COORDINATOR: // 书记
                $instance = new SchoolCoordinator($user->getSchoolId());
                break;
            case Title::CLASS_ADVISER: // 班主任
                $instance = new ClassAdviser($user);
                break;
            case Title::DEPARTMENT_LEADER: // 系主任
                $instance = new DepartmentManager($user);
                break;
            case Title::ORGANIZATION_LEADER: // 部门主管
                $instance = new DepartmentManager($user);
                break;
            case Title::ORGANIZATION_DEPUTY: // 部门付主管
                $instance = new DepartmentManager($user);
                break;
            case Title::GRADE_ADVISER: // 年级组长
                $instance = new GradeAdviser($user);
                break;
            default:
                break;
        }
        return $instance;
    }
}