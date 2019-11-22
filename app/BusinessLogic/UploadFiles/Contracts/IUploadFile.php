<?php

namespace App\BusinessLogic\UploadFiles\Contracts;

interface IUploadFile
{
    /**
     * 上传文件
     * @return mixed
     */
    public function upload();

    /**
     * 移动文件
     * @return mixed
     */
    public function moveFile();

    /**
     * 删除文件
     * @return mixed
     */
    public function delFile();

}
