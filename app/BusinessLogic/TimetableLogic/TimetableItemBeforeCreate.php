<?php
/**
 * 在Item 被插入到数据之前, 需要执行的业务逻辑
 */
namespace App\BusinessLogic\TimetableLogic;

use App\Dao\Timetable\TimetableItemDao;
use Illuminate\Http\Request;
use App\Dao\Users\UserDao;

class TimetableItemBeforeCreate
{
    /**
     * @var Request
     */
    private $request;
    private $itemData;
    private $schoolId;
    private $userUuid;

    /**
     * @var TimetableItemDao
     */
    private $itemDao;

    /**
     * @var UserDao
     */
    private $userDao;
    private $currentUser;

    public $checked = false;
    public $errorMessage = '';

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->schoolId = $request->get('school');
        $this->userUuid = $request->get('user');
        $this->itemData = $request->get('timetableItem');
        $this->itemData['school_id'] = $this->schoolId;
        $this->userDao = new UserDao();
        $this->itemDao = new TimetableItemDao();
    }

    /**
     * Todo: 课程表项目创建之前的检查工作需要完成
     * @return $this
     */
    public function check(){
        $this->currentUser = $this->userDao->getUserByIdOrUuid($this->userUuid);
        $this->checked = $this->currentUser && $this->currentUser->isSchoolAdminOrAbove();
        if(!$this->checked){
            // 用户没有权限做插入的操作
            $this->errorMessage = '您没有做创建课程表项的权限';
            return $this;
        }

        if(isset($this->itemData['id'])){
            // 这是个更新操作, 所以是可以通过的
            $this->checked = true;
            return $this;
        }

        $found = $this->itemDao->hasAnyOneTakenThePlace($this->itemData);
        if($found){
            // 表示已经被占用了
            $this->checked = false;
            $this->errorMessage = '您选择的时间段已经被: ' . $found->describeItself() . ' 占用了.';
            return $this;
        }
        return $this;
    }

    /**
     * 创建 Item
     * @return \App\Models\Timetable\TimetableItem
     */
    public function create(){
        if($this->checked){
            $this->itemData['last_updated_by'] = $this->currentUser->id;
            return $this->itemDao->createTimetableItem($this->itemData);
        }
        return null;
    }
}