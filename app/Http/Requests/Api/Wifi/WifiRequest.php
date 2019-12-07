<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 19/11/19
 * Time: 11:33 AM
 */
namespace App\Http\Requests\Api\Wifi;

/**
 * Class WifiRequest
 * @package App\Http\Requests\Api\Wifi
 */
class WifiRequest extends \App\Http\Requests\ApiRequest
{
   public $rules = [];

   protected $messages = [
      'uuid.required' => '参数不能为空' ,
      'user_mobile_source.required' => '请选择运营商' ,
      'user_mobile_source.integer' => '运营商值错误' ,
      'user_mobile_source.between' => '运营商值错误' ,
      'user_mobile_password.required' => '请填写手机卡密码' ,
   ];

   public function rules ()
   {
      $rules = $this->rules;
      $getActionName = request()->route()->getActionName();

      // 校园网首页
      if(stripos($getActionName,'index_wifi'))
      {
         // $rules[ 'uuid' ] = 'required'; // uuid
      }

      // 用户同意wifi协议
      if(stripos($getActionName,'edit_agreement_info'))
      {
         // $rules[ 'uuid' ] = 'required'; // uuid
      }

      // 修改运营商信息
      if(stripos($getActionName,'edit_operator_info'))
      {
         $rules[ 'user_mobile_source' ] = 'required|integer|between:1,4'; // 运营商(1:移动,2:联通,3:电信,4:其他)
         $rules[ 'user_mobile_password' ] = 'required'; // 手机卡密码
      }

      return $rules;
   }

   public function messages ()
   {
      return $this->messages;
   }

}
