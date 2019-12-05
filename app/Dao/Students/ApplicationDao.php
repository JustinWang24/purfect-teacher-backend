<?php
namespace App\Dao\Students;

use App\Models\Students\ApplicationMedia;
use App\Utils\JsonBuilder;
use App\Models\Students\Application;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class ApplicationDao
{

    /**
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $mediaId = $data['media_id'];
        unset($data['media_id']);
        DB::beginTransaction();
        try{
            $result = Application::create($data);

            // 创建申请文件关联
            if(!empty($mediaId)) {
                foreach ($mediaId as $key => $val) {
                    $insert = ['application_id' => $result->id, 'media_id'=>$val];
                    ApplicationMedia::create($insert);
                }
            }
            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功',$result);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            DB::rollBack();
            return new MessageBag(JsonBuilder::CODE_ERROR, '创建失败'.$msg);

        }
    }


    /**
     * 根据学校获取申请
     * @param $schoolId
     * @return mixed
     */
    public function getApplicationBySchoolId($schoolId) {
        return Application::where('school_id', $schoolId)
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 获取详情
     * @param $id
     * @return mixed
     */
    public function getApplicationById($id) {
        return Application::where('id',$id)->first();
    }

    /**
     * 修改状态
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatusById($id, $data) {
        return Application::where('id', $id)->update($data);
    }


    /**
     * 根据用户获取申请列表
     * @param $userId
     * @param $simpleness
     * @return mixed
     */
    public function getApplicationByUserId($userId, $simpleness = true) {
        $field = '*';
        if($simpleness) {
            $field = ['id', 'application_type_id', 'created_at', 'status'];
        }
        return Application::where('user_id', $userId)
            ->select($field)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }




}
