<?php


namespace App\Http\Requests\Login;

use App\Http\Requests\MyStandardRequest;

class LoginRequest extends MyStandardRequest
{
    public function authorize()
    {
        return true;
    }


    /**
     * 登录 提交的数据
     * @return array
     */
    public function getUserDevice()
    {
        return [
            'platform'       => $this->getPlatform(),
            'model'          => $this->getModel(),
            'type'           => $this->getDeviceType(),
            'device_number'  => $this->getDeviceNumber(),
            'push_id'        => $this->getPushId(),
            'version_number' => $this->getVersion()
        ];
    }

    /**
     * 手机平台
     */
    public function getPlatform()
    {
        return $this->get('platform', null);
    }

    /**
     * 手机型号
     */
    public function getModel()
    {
        return $this->get('model',null);
    }

    /**
     * 设备类型
     */
    public function getDeviceType()
    {
        return $this->get('type',null);
    }

    /**
     * 设备号
     */
    public function getDeviceNumber()
    {
         return $this->get('device_number',null);
    }

    /**
     * 推送ID
     */
    public function getPushId()
    {
         return $this->get('push_id','');
    }

    /**
     * APP版本
     * 6: 学生端, 9: 教师端
     */
    public function getAppType()
    {
        return $this->get('app_type');
    }

    /**
     * 获取手机号
     */
    public function getMobile()
    {
        return $this->get('mobile', null);
    }

    /**
     * 获取密码
     * @return string|null
     */
    public function getPassword()
    {
        return $this->get('password', null);
    }


}
