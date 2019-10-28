<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 26/10/19
 * Time: 8:17 PM
 */

namespace App\BusinessLogic\TimetableLogic;
use App\Dao\Timetable\TimetableItemDao;
use Illuminate\Http\Request;
use App\Dao\Users\UserDao;

class TimetableItemBeforeUpdate
{
    /**
     * @var Request
     */
    protected $request;
    protected $itemData;
    protected $schoolId;
    protected $userUuid;
    /**
     * @var UserDao
     */
    protected $userDao;
    protected $currentUser;

    private $checked = false;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->schoolId = $request->get('school');
        $this->userUuid = $request->get('user');
        $this->itemData = $request->get('timetableItem');
        $this->userDao = new UserDao();
    }

    /**
     * Todo: 课程表项目更新之前的检查工作需要完成
     * @return $this
     */
    public function check(){
        $this->currentUser = $this->userDao->getUserByIdOrUuid($this->userUuid);
        $this->checked = $this->currentUser && $this->currentUser->isSchoolAdminOrAbove();
        return $this;
    }

    /**
     * 更新 Item
     * @return false
     */
    public function update(){
        if($this->checked){
            $dao = new TimetableItemDao();
            $this->itemData['last_updated_by'] = $this->currentUser->id;
            return $dao->updateTimetableItem($this->itemData);
        }
        return false;
    }
}