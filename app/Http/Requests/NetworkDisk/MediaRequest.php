<?php


namespace App\Http\Requests\NetworkDisk;


use App\Models\NetworkDisk\Media;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Ramsey\Uuid\Uuid;
use App\Dao\NetworkDisk\CategoryDao;
use App\Http\Requests\MyStandardRequest;

class MediaRequest extends MyStandardRequest
{

    /**
     * 获取uuid
     * @return mixed
     */
    public function getUuId() {
        return $this->get('uuid',null);
    }


    /**
     * 获取Category的uuid
     * @return mixed
     */
    public function getCategory() {
        return $this->get('category',null);
    }

    /**
     * @return string
     */
    public function getFileUuid(){
        return $this->get('file_uuid', null);
    }

    /**
     * 获取上传文件
     * @return array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|null
     */
    public function getFile() {
        return $this->file('file',null);
    }


    /**
     * 文件的关键字, 用于查询
     * @return mixed
     */
    public function getKeywords() {
        $keywords = $this->get('keywords',null);
        return !is_null($keywords)?$keywords:$this->getFile()->getClientOriginalName();
    }


    /**
     * 文件的描述文字
     * @return mixed
     */
    public function getDescription() {
        $description = $this->get('description',null);
        return !is_null($description)?$description:$this->getFile()->getClientOriginalName();
    }


    /**
     * 获取代上传文件大小
     * @return mixed
     */
    public function getSize() {
        return $this->get('size',null);
    }

    /**
     * 获取上传数据
     * @return array|IMessageBag
     * @throws \Exception
     */
    public function getUpload() {
        $file = $this->getFile();
        /**
         * @var User $user
         */
        $user = $this->user();

        $path = Media::DEFAULT_UPLOAD_PATH_PREFIX.$user->id; // 上传路径
        $categoryDao = new CategoryDao();
        $category = $categoryDao->getCateInfoByUuId($this->getCategory());

        $auth = $category->isOwnedByUser($user);
        if(!$auth && !$user->isSchoolAdminOrAbove()) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'非用户本人,不能上传');
        }
        try{
            $uuid = Uuid::uuid4()->toString();
            // 这里一定要使用 storeAs, 因为 Symfony 的 Mime type guesser 似乎有 bug, 一些文件总是得到 zip 的类型
            $url = $file->storeAs($path, $uuid.'.'.$file->getClientOriginalExtension()); // 上传并返回路径
            $data = [
                'category_id' => $category->id,
                'uuid'        => $uuid,
                'user_id'     => $this->user()->id, // 文件和目录的所有着, 应该一直保持一致
                'keywords'    => $this->getKeywords(),
                'description' => $this->getDescription(),
                'file_name'   => $file->getClientOriginalName(),
                'size'        => $file->getSize(),
                'url'         => Media::ConvertUploadPathToUrl($url),
                'type'        => Media::ParseFileType($file->getClientOriginalExtension()),
                'driver'      => Media::DRIVER_LOCAL,
            ];
            $msgBag = new MessageBag(JsonBuilder::CODE_SUCCESS);
            $msgBag->setData($data);
            return $msgBag;
        }catch (\Exception $exception){
            return new MessageBag(JsonBuilder::CODE_ERROR, $exception->getMessage());
        }
    }
}
