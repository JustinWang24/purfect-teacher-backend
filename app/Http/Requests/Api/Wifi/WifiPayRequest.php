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
   class WifiPayRequest extends \App\Http\Requests\ApiRequest
   {
      public function authorize ()
      {
         return true;
      }

      public $rules = [];

      protected $messages = [
         'uuid.required' => '参数不能为空' ,
         'page.required' => '分页不能为空' ,
         'page.integer' => '分页必须为整数' ,
      ];

      public function rules ()
      {
         $rules = $this->rules;

         $getActionName = request()->route()->getActionName();

         // 充值记录
         if(stripos($getActionName,'list_recharge_info'))
         {
            // $rules[ 'page' ] = 'required|integer';
         }
         return $rules;
      }

      public function messages ()
      {
         return $this->messages;
      }
   }
