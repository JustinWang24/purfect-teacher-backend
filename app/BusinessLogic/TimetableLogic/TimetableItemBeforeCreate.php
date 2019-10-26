<?php
/**
 * 在Item 被插入到数据之前, 需要执行的业务逻辑
 */
namespace App\BusinessLogic\TimetableLogic;

use App\Dao\Timetable\TimetableItemDao;
use Illuminate\Http\Request;
use App\Dao\Users\UserDao;
use App\User;

class TimetableItemBeforeCreate
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
     * @return $this
     */
    public function check(){
        $this->currentUser = $this->userDao->getUserByIdOrUuid($this->userUuid);
        $this->checked = $this->currentUser && $this->currentUser->isSchoolAdminOrAbove();
        return $this;
    }

    /**
     * 创建 Item
     * @return \App\Models\Timetable\TimetableItem
     */
    public function create(){
        if($this->checked){
            $dao = new TimetableItemDao();
            $this->itemData['school_id'] = $this->schoolId;
            $this->itemData['last_updated_by'] = $this->currentUser->id;
            return $dao->createTimetableItem($this->itemData);
        }
        return null;
    }
}