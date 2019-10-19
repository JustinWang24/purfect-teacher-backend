<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 19/10/19
 * Time: 12:24 AM
 */

namespace App\BusinessLogic\UsersListPage\Impl;
use App\BusinessLogic\UsersListPage\Contracts\IUsersListPageLogic;
use Illuminate\Http\Request;
use App\Dao\Schools\CampusDao;
use App\Dao\Schools\InstituteDao;

abstract class AbstractUsersListLogic implements IUsersListPageLogic
{
    /**
     * @var Request
     */
    protected $request;
    protected $id;

    /**
     * RegisteredStudentsListLogic constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->id = $request->get('uuid');
    }

    public function getParentModel()
    {
        $result = [];
        $user = $this->request->user();
        switch ($this->request->get('by')){
            case 'campus':
                $campusDao = new CampusDao($user);
                $result = $campusDao->getCampusById($this->id);
                break;
            case 'institute':
                $dao = new InstituteDao($user);
                $result = $dao->getInstituteById($this->id);
                break;
            default:
                break;
        }
        return $result;
    }

    public function getAppendedParams()
    {
        return [
            'type'=>$this->request->get('type'),
            'uuid'=>$this->id,
            'by'=>$this->request->get('by')
        ];
    }

    public function getReturnPath()
    {
        $path = 'school_manager.school.view';
        switch ($this->request->get('by')){
            case 'campus':
                $path = 'school_manager.school.view';
                break;
            case 'institute':
                break;
            default:
                break;
        }
        return route($path);
    }
}