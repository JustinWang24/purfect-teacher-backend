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
   class WifiCableRequest extends \App\Http\Requests\ApiRequest
   {
      public function authorize ()
      {
         return true;
      }

      public $rules = [];

      protected $messages = [
         'uuid.required' => '参数不能为空' ,

         // 开通有线
         'wifiid.required' => '参数不能为空' ,
         'wifiid.integer' => '参数错误' ,
         'addressoneid.required' => '请选择宿舍楼' ,
         'addressoneid.integer' => '请选择宿舍楼' ,
         'addressoneid_name.required' => '请选择宿舍楼' ,
         'addresstwoid.required' => '请选择楼层' ,
         'addresstwoid.integer' => '请选择楼层' ,
         'addresstwoid_name.required' => '请选择楼层' ,
         'addressthreeid.required' => '请选择房间' ,
         'addressthreeid.integer' => '请选择房间' ,
         'addressthreeid_name.required' => '请选择房间' ,
         'addressfourid.required' => '请选择端口' ,
         'addressfourid.integer' => '请选择端口' ,
         'addressfourid_name.required' => '请选择端口' ,
      ];

      public function rules ()
      {
         $rules = $this->rules;

         $getActionName = request()->route()->getActionName();

         // 已开通有线
         if(stripos($getActionName,'index_cable'))
         {
            $rules[ 'uuid' ] = 'required';
         }

         // 宿舍楼地址列表
         if(stripos($getActionName,'list_category_info'))
         {
            $rules[ 'uuid' ] = 'required';
         }

         // 开通有线
         if ( stripos ( $getActionName , 'add_cable_info' ) )
         {
            $rules[ 'uuid' ]                = 'required';
            $rules[ 'wifiid' ]              = 'required|integer'; // wifiid
            $rules[ 'addressoneid' ]        = 'required|integer'; // 有线(宿舍楼id)
            $rules[ 'addressoneid_name' ]   = 'required'; // 有线(宿舍楼名称)
            $rules[ 'addresstwoid' ]        = 'required|integer'; // 有线(楼层id)
            $rules[ 'addresstwoid_name' ]   = 'required'; // 有线(楼层名称)
            $rules[ 'addressthreeid' ]      = 'required|integer'; // 有线(房间id)
            $rules[ 'addressthreeid_name' ] = 'required'; // 有线(房间名称)
            $rules[ 'addressfourid' ]       = 'required|integer'; // 有线(端口id)
            $rules[ 'addressfourid_name' ]  = 'required'; // 	有线(端口名称)
         }

         return $rules;
      }

      public function messages ()
      {
         return $this->messages;
      }
   }
