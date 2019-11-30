<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/11/19
 * Time: 6:11 PM
 */

namespace App\Models\Contract;


interface ContentHolder
{
    /**
     * 根据手机号码获取内容, 该内容用来发送短信
     * @param $mobile
     * @return array
     */
    public function getContentByMobile($mobile): array ;
}