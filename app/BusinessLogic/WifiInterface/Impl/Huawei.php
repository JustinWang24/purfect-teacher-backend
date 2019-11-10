<?php
/**
 * Created by PhpStorm.
 * User: kui.zhang
 * Date: 05/11/19
 * Time: 11:49 PM
 */

namespace App\BusinessLogic\WifiInterface\Impl;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Cache;
use App\BusinessLogic\WifiInterface\Contracts\WifiInterface;

class Huawei implements WifiInterface
{
   private static $huaweiVo = 'huaweiKeyToken'; // 华为key
   private static $userName = 'yezilu100@purfect.cn'; // 账号
   private static $password = '@1234567890Aa'; // 密码
   private static $apiUrl   = 'https://naas.huaweicloud.com:18002'; // 接口地址

   private static $huaweiIdentify     = 'huaweiIdentify'; // 本地保存token的key
   private static $huaweiIdentifyTime = 8; // 本地保存token 的有效期
   private static $huaweiAccessToken  = null; // 华为接口请求的访问token

   public function __construct ()
   {
      if ( Cache::has ( self::$huaweiIdentify ) == false )
      {
         $tokenArr = $this->getToken ();

         self::$huaweiAccessToken = $tokenArr['token_id'];

         // TODO.....华为有效获取时间有错，返回的时间已过期
         Cache::put ( self::$huaweiIdentify , $tokenArr , self::$huaweiIdentifyTime );

      } else {

         $tokenArr = Cache::get ( self::$huaweiIdentify);

         self::$huaweiAccessToken = $tokenArr['token_id'];
      }
   }

   /**
    * Func 添加账号
    */
   public function addAccount()
   {
      $param[ 'userGroupId' ]     = '40a43aa7-3b16-4474-bce6-10d4fdac84a7'; // 用户组ID。->  是 -> 实例：51cdcbe3-5e14-492b-ab27-86950b661d19
      $param[ 'password' ]        = 'Test@1234'; // 密码。->  是  -> 8~128个字符 // 这里有一个坑：华为接口验证了复杂性
      $param[ 'passwordConfirm' ] = 'Test@1234'; // 确认密码。 ->  是 -> 8~128个字符
      $param[ 'userName' ]        = '18888888888'; // 用户名。  ->   是  ->   1~64个字符。
      $param[ 'vaildPeriod' ]     = '2019-11-09 00:36:19'; // 用户到期时间。->  否

      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/users" )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();

      $result = json_decode ($result,true);

      if($result['errcode'] == '0')
      {
         // 返回uuid
         return (String)$result[ 'data' ][ 'id' ];

      } else {
         return false;
      }
   }

   /**
    * Func 修改用户信息(这里用到只有修改wifi密码)
    */
   public function editAccount()
   {
      $userInfo['uuid']     = 'ac5dd97d-0d78-40df-8073-e869d6c26630';

      $param[ 'userName' ]     = '18888888888'; // 用户名。-> 是  -> 1~64个字符。(userName是注册的wifi账号)
      $param[ 'vaildPeriod' ]     = '2019-11-20 02:36:19'; // 用户到期时间。->  否

      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/users/".$userInfo['uuid'] )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->put ();

      $result = json_decode ( $result , true );
      return $result[ 'errcode' ] == '0' ? true : false;
   }

   /**
    * Func 修改账号密码
    */
   public function editAccountPassword()
   {
   }

   /**
    * Func 注销账户
    */
   public function closeAccount()
   {
      $userInfo['uuid']     = '30e81b40-fbdc-4d25-b721-9ed64965996b';

      $param[ 'allUserIds' ] = [$userInfo['uuid']]; // 用户ID集合。->  是 这里是UDID(35da2f2e-f29d-4ae2-b81e-0142a309f6de)

      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/users/batch-delete" )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();

      $result = json_decode ($result,true);
      return $result[ 'errcode' ] == '0' ? true : false;
   }

   /**
    * Func 修改账号服务组
    */
   public function editAccountServiceGroup()
   {
   }

   /**
    * Func 账号上线操作
    */
   public function AccountOnline()
   {
   }

   /**
    * Func 账号下线操作
    */
   public function AccountOffline()
   {
   }

   /**
    * Func 查询账号是否在线
    */
   public function checkAccountOnline()
   {
   }


   /**
    * Func 获取Token
    */
   public function getToken ()
   {
      $param  = [ 'userName' => self::$userName , 'password' => self::$password ];
      $result = Curl::to ( self::$apiUrl . "/controller/v2/tokens" )
                    ->withHeaders ( [ 'Content-Type: application/json' ] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();
      $result = json_decode ($result,true);
      if($result['errcode'] == '0')
      {
         return $result[ 'data' ];
      } else {
         return false;
      }
   }


   /**
    * Func 创建用户组
    */
   public function AddUsergroups ()
   {
      $param  = [ 'parentId' => '63c293e2-4b88-74ae-747b-94aa2e19733f' , 'name' => '老师' ];
      $param  = [ 'name' => '63c293e2-4b88-74ae-747b-94aa2e19733f' ];
      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/usergroups" )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();
      $result = json_decode ($result,true);
      dd($result);
   }

   /**
    * Func 查询用户组
    */
   public function findUsergroup ()
   {
     /* 0 => array:10 [▼
      "id" => "20326e51-42a3-47e5-bb36-083c276db2e3"
      "description" => null
      "fullPathName" => "ROOT"
      "bsid" => "00000"
      "parentId" => null
      "name" => "ROOT"
      "address" => null
      "postalCode" => null
      "adminEmail" => null
      "orderId" => 0
    ]
    1 => array:10 [▼
      "id" => "7eb97a44-dd27-4a19-ae13-4556d4d56bfd"
      "description" => null
      "fullPathName" => "ROOT\Guest"
      "bsid" => "00000-00000"
      "parentId" => "20326e51-42a3-47e5-bb36-083c276db2e3"
      "name" => "Guest"
      "address" => null
      "postalCode" => null
      "adminEmail" => null
      "orderId" => 0
    ]
    2 => array:10 [▼
      "id" => "2d3f4e1a-660a-4ce3-9f93-fbc4d907febd"
      "description" => ""
      "fullPathName" => "ROOT\老师-禁止删除"
      "bsid" => "00000-00002"
      "parentId" => "20326e51-42a3-47e5-bb36-083c276db2e3"
      "name" => "老师-禁止删除"
      "address" => ""
      "postalCode" => ""
      "adminEmail" => ""
      "orderId" => 2
    ]
    3 => array:10 [▼
      "id" => "40a43aa7-3b16-4474-bce6-10d4fdac84a7"
      "description" => ""
      "fullPathName" => "ROOT\学生-禁止删除"
      "bsid" => "00000-00001"
      "parentId" => "20326e51-42a3-47e5-bb36-083c276db2e3"
      "name" => "学生-禁止删除"
      "address" => ""
      "postalCode" => ""
      "adminEmail" => ""
      "orderId" => 1
    ]*/
      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/usergroups" )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->get ();
      $result = json_decode ($result,true);
      dd($result);
   }


}