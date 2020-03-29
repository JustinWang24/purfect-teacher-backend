<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 12:55 PM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Schools\Room;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class RoomDao
{
    /**
     * @var User $currentUser
     */
    private $currentUser;
    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * @param $buildingId
     * @return Collection
     */
    public function getRoomsByBuilding($buildingId){
        return Room::where('building_id',$buildingId)->get();
    }

    /**
     * @param $campusId
     * @return Collection
     */
    public function getRoomsByCampus($campusId){
        return Room::where('campus_id',$campusId)->paginate();
    }

    /**
     * @param $id
     * @return Room
     */
    public function getRoomById($id){
        return Room::find($id);
    }


    /**
     * @param $map
     * @param $field
     * @return mixed
     */
    public function getRooms($map,$field='*')
    {
        return Room::where($map)->select($field)->get();
    }

    /**
     * @param $map
     * @param $field
     * @return mixed
     */
    public function getRoomsPaginate($map,$field='*')
    {
        return Room::where($map)->select($field)->paginate();
    }

    /**
     * @param array $idArr
     * @param string $field
     * @return mixed
     */
    public function getRoomsByIdArr($idArr,$field='*') {
         return Room::whereIn('id',$idArr)->select($field)->get();
    }


    /**
     * 添加房间
     * @param $data
     * @param $user
     * @param $file
     * @return MessageBag
     * @throws \Exception
     */
    public function createRoom($data, $user, $file){
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        $info = $this->getRoomByCodeAndSchoolId($data['school_id'], $data['name']);
        if(!is_null($info)) {
            $messageBag->setMessage('该编号已存在');
            return $messageBag;
        }

        $re = $this->upload($file,$user);
        if(!is_null($re)) {
            $data['url'] = $re['url'];
            $data['file_name'] = $re['file_name'];
        }

        $result = Room::create($data);
        if($result) {
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData(['room_id'=>$result['id']]);
            $messageBag->setMessage('房间保存成功');
        } else {
            $messageBag->setMessage('房间保存失败');
        }
        return $messageBag;
    }


    /**
     * 上传文件
     * @param $file
     * @param $user
     * @return array|null
     * @throws \Exception
     */
    public function upload($file, $user) {
        if(!is_null($file)) {
            $path = Room::DEFAULT_UPLOAD_PATH_PREFIX.$user->id; // 上传路径
            $uuid = Uuid::uuid4()->toString();
            $url = $file->storeAs($path, $uuid.'.'.$file->getClientOriginalExtension()); // 上传并返回路径
            $url = Room::ConvertUploadPathToUrl($url);
            $fileName = $file->getClientOriginalName();
            return ['url'=>$url,'file_name'=>$fileName];
        }
        return null;
    }



    /**
     * 删除房间数据
     * @param $roomId
     * @return mixed
     */
    public function deleteRoom($roomId){
        return Room::where('id',$roomId)->delete();
    }


    /**
     * 更新 Room 数据
     * @param $data
     * @param $user
     * @param $file
     * @return MessageBag
     * @throws \Exception
     */
    public function updateRoom($data,$user, $file){
        $id = $data['id'];
        unset($data['id']);
        $message = new MessageBag(JsonBuilder::CODE_ERROR);
        $re = $this->upload($file,$user);
        if(!is_null($re)) {
            $data['url'] = $re['url'];
            $data['file_name'] = $re['file_name'];
        }
        $result = Room::where('id', $id)->update($data);
        if($result === false) {
            $message->setMessage('更新失败');
        } else {
            $message->setMessage('更新成功');
            $message->setCode(JsonBuilder::CODE_SUCCESS);
        }
        return $message;
    }


    /**
     * @param array $map
     * @param array $field
     * @return mixed
     */
    protected function getRoomList($map, $field) {
        return Room::where($map)->select($field)->get();
    }


    /**
     * 通过建筑ID获取教室
     * @param $buildingId
     * @return mixed
     */
    public function getRoomByBuildingId($buildingId) {
        $field = ['id', 'building_id', 'name', 'type','exam_seats', 'seats'];
        $map = ['building_id'=>$buildingId, 'type'=>Room::TYPE_CLASSROOM];
        $result = $this->getRoomList($map,$field);
        return $result;
    }

    /**
     * 获取房间列表
     * @param $schoolId
     * @param $type
     * @param null $buildingId
     * @return Collection
     */
    public function getRoomByType($schoolId, $type, $buildingId = null){
        $where = [
            ['school_id','=',$schoolId],
            ['type','=',$type],
        ];
        if($buildingId){
            $where[] = ['building_id','=',$buildingId];
        }
        return Room::select(['building_id','name','seats'])->where($where)->with('building:id,name')
            ->orderBy('building_id','asc')->get();
    }


    /**
     * 根据编号查询房间
     * @param $schoolId
     * @param $code
     * @return mixed
     */
    public function getRoomByCodeAndSchoolId($schoolId, $code) {
        $map = ['school_id'=>$schoolId, 'name'=>$code];
        return Room::where($map)->first();
    }
}
