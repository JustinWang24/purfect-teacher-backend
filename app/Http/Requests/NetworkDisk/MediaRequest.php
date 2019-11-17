<?php


namespace App\Http\Requests\NetworkDisk;


use App\Models\NetworkDisk\Media;
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
        return $this->get('description',null);
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
        $path = Media::DEFAULT_UPLOAD_PATH_PREFIX.$this->user()->id; // 上传路径
        $categoryDao = new CategoryDao();
        $category = $categoryDao->getCateInfoByUuId($this->getCategory());

        try{
            $url = $file->store($path); // 上传并返回路径
            $data = [
                'category_id' => $category->id,
                'uuid'        => Uuid::uuid4()->toString(),
                'user_id'     => $this->user()->id,
                'keywords'    => $this->getKeywords(),
                'description' => $this->getDescription(),
                'file_name'   => $file->getClientOriginalName(),
                'size'        => $file->getSize(),
                'url'         => Media::ConvertUploadPathToUrl($url),
                'type'        => Media::ParseFileType($url),
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
