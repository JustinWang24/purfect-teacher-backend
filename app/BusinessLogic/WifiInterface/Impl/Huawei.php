<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
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
    * Func 新增用户
    *
    * @param[---系统提交参数
    *    'userGroupId'=>'5b988058-e092-4310-a0ad-575e9b50e2db'，// 用户组ID
    *    'userName'=>'18888888888'，// 账号
    *    'password'=>'111111'， // 密码
    *    'vaildPeriod'=>'2019-12-06 18:00:99'，// wifi失效时间
    * ]
    * @return [---系统返回参数
    *    'status'=>true|false,'message'=>'','data'=>[]
    * ]
    * @param $userInfo {---华为请求参数
    *    "userGroupId" : "51cdcbe3-5e14-492b-ab27-86950b661d19",// 用户组ID
    *    "password" : "Test@1234",// 密码
    *    "passwordConfirm" : "Test@1234",// 确认密码
    *    "userName" : "test",// 用户名
    *    "email" : "aaa@huawei.com",// 邮箱
    *    "telephone" : "13112345678",// 联系电话
    *    "vaildPeriod" :  "", // 用户到期时间
    *    "description" : "0",// 用户描述信息
    *    "nextUpdateUserpass" : true// 下次登录修改密码
    *    }
    * @return {---华为接口响应参数
    *    "errcode" : "0",
    *    "errmsg" :  "",
    *    "data" : {
    *       "id" : "5b988058-e092-4310-a0ad-575e9b50e2db", // 用户ID
    *       "userGroupId" : "51cdcbe3-5e14-492b-ab27-86950b661d19", // 用户组ID
    *       "userType" : 0, // 用户类型
    *       "modifyDate" : "2017-11-06T02:53:14Z", // 账号创建时间
    *       "userName" : "test", // 用户名
    *       "email" : "aaa@huawei.com",// 邮箱
    *       "telephone" : "13112345678", // 联系电话
    *       "vaildPeriod" :  "", // 用户到期时间
    *       "description" : "0", // 用户描述信息
    *       "nextUpdateUserpass" : true // 下次登录修改密码
    *    }
    * }

    */
   public function addAccount ( $userInfo = [] )
   {
      if ( empty( $userInfo[ 'userGroupId' ] ) )
      {
         return [ 'status' => false , 'message' => '用户组ID不能为空' ];
      }
      if ( empty( $userInfo[ 'userName' ] ) )
      {
         return [ 'status' => false , 'message' => '用户名不能为空' ];
      }
      if ( empty( $userInfo[ 'password' ] ) )
      {
         return [ 'status' => false , 'message' => '密码不能为空' ];
      }
      if ( empty( $userInfo[ 'vaildPeriod' ] ) )
      {
         return [ 'status' => false , 'message' => '用户到期时间不能为空' ];
      }
      // 提交参数
      $param[ 'userGroupId' ]        = $userInfo[ 'userGroupId' ];
      $param[ 'password' ]           = $userInfo[ 'password' ];
      $param[ 'passwordConfirm' ]    = $userInfo[ 'password' ];
      $param[ 'userName' ]           = $userInfo[ 'mobile' ];
      $param[ 'vaildPeriod' ]        = $userInfo[ 'vaild_data' ];
      $param[ 'nextUpdateUserpass' ] = false;
      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/users" )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();
      $resultArr = json_decode ( $result , true );
      if ( $resultArr[ 'errcode' ] == '0' && ! empty( $resultArr[ 'data' ] ) )
      {
         // 注册成功
         return [ 'status' => true , 'message' => 'ok' , 'data' => $resultArr[ 'data' ] ];
      } else {
         return [ 'status' => false , 'message' => '新增失败,请稍后重试' ];
      }
   }

   /**
    * Func 修改wifi基础信息(包括wifi时长)
    *
    * @param[---系统提交参数
    *    'uuid'=>'5b988058-e092-4310-a0ad-575e9b50e2db'，// 用户uuid
    *    'mobile'=>'18888888888'，// 账号
    *    'vaildPeriod'=>'2019-12-06 18:00:99'，// wifi失效时间
    * ]
    * @return [---系统返回参数
    *    'status'=>true|false,'message'=>''
    * ]
    * @param $userInfo {---华为请求参数
    *    "userName" : "18888888888",// 用户名
    *    "vaildPeriod" : "2019-12-06 18:00:99",// 用户到期时间
    *    "nextUpdateUserpass" : false,// 下次登录修改密码
    *    }
    * @return {---华为接口响应参数
    *    "errcode" : "0",
    *    "errmsg" :  ""
    * }
    */
   public function editAccount ( $userInfo = [] )
   {
      if ( empty( $userInfo[ 'uuid' ] ) )
      {
         return [ 'status' => false , 'message' => '用户参数错误' ];
      }
      if ( empty( $userInfo[ 'mobile' ] ) )
      {
         return [ 'status' => false , 'message' => 'wifi账号不能为空' ];
      }
      if ( empty( $userInfo[ 'vaildPeriod' ] ) )
      {
         return [ 'status' => false , 'message' => 'wifi时长不能为空' ];
      }
      // 提交参数
      $param[ 'userName' ]    = $userInfo[ 'mobile' ];
      $param[ 'vaildPeriod' ] = $userInfo[ 'vaildPeriod' ];
      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/users/".$userInfo[ 'uuid' ] )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->put ();
      $resultArr = json_decode ( $result , true );
      if ( $resultArr[ 'errcode' ] == '0' )
      {
         return [ 'status' => true , 'message' => '操作成功' ];
      } else {
         return [ 'status' => false , 'message' => '操作失败,请稍后重试' ];
      }
   }

   /**
    * Func 修改账号密码
    */
   public function editAccountPassword()
   {
   }

   /**
    * Func 注销账户
    *
    * @param[---系统提交参数
    *    'uuid'=>'5b988058-e092-4310-a0ad-575e9b50e2db'，// 用户uuid
    * ]
    * @return [---系统返回参数
    *    'status'=>true|false,'message'=>''
    * ]
    * @param $userInfo {---华为请求参数
    *    "allUserIds" : ["35da2f2e-f29d-4ae2-b81e-0142a309f6de"]
    *    }
    * @return {---华为接口响应参数
    *    "errcode" : "0",
    *    "errmsg" :  ""
    * }
    */
   public function closeAccount ( $userInfo = [] )
   {
      if ( empty( $userInfo[ 'uuid' ] ) )
      {
         return [ 'status' => false , 'message' => '用户参数错误' ];
      }
      $param[ 'allUserIds' ] = [$userInfo['uuid']];
      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/accountservice/users/batch-delete" )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'.self::$huaweiAccessToken ] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();
      $resultArr = json_decode ($result,true);
      if ( $resultArr[ 'errcode' ] == '0' )
      {
         return [ 'status' => true , 'message' => '操作成功' ];
      } else {
         return [ 'status' => false , 'message' => '操作失败,请稍后重试' ];
      }
   }

   /**
    * Func 修改账号服务组
    */
   public function editAccountServiceGroup()
   {
   }

   /**
    * Func 账号上线操作
    * @$userInfo['authType'] 认证类型，用户名密码：1；匿名认证：2；短信认证：3；passcode认证：6 默认为用户名密码认证(1)
    * @$userInfo['deviceMac'] 设备MAC地址
    * @$userInfo['ssid'] AP SSID名称
    * @$userInfo['terminalIp'] 终端IP地址，支持Ipv4和Ipv6地址
    * @$userInfo['terminalMac'] 该字段可能包含敏感数据，请妥善做好加密保护！典型的敏感数据包括认证凭据（如口令、动态令牌卡、短信随机密码）、银行帐号、大批量个人数据和用户通信内容等。终端MAC
    * @$userInfo['userName']   用户名
    * @$userInfo['password']   该字段可能包含敏感数据，请妥善做好加密保护！典型的敏感数据包括认证凭据（如口令、动态令牌卡、短信随机密码）、银行帐号、大批量个人数据和用户通信内容等。用户密码
    *
    * @return ['status'=>true|false,'message'=>'']
    */
   public function AccountOnline ( $userInfo = [] )
   {
      $param[ 'authType' ]    = 1;
      $param[ 'deviceMac' ]   = (String)$userInfo[ 'deviceMac' ];
      $param[ 'ssid' ]        = (String)$userInfo[ 'ssid' ];
      $param[ 'terminalIp' ]  = (String)$userInfo[ 'terminalIp' ];
      $param[ 'terminalMac' ] = (String)$userInfo[ 'terminalMac' ];
      $param[ 'userName' ]    = (String)$userInfo[ 'userName' ];
      $param[ 'password' ]    = (String)$userInfo[ 'password' ];
      $result = Curl::to ( self::$apiUrl . '/controller/campus/v1/portalauth/login' )
                    ->withHeaders ( [ 'Content-Type: application/json'] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();
      $resultArr = json_decode ( $result , true );
      if ( isset($resultArr[ 'errcode' ]) && $resultArr[ 'errcode' ] == '0' && $resultArr[ 'data' ][ 'psessionid' ] != '' )
      {
         return [ 'status' => true , 'message' => '上线成功' , 'data' => [ 'psessionid' => $resultArr[ 'data' ][ 'psessionid' ] ] ];
      } else {
         return [ 'status' => false , 'message' => '上线失败,请稍后重试' ];
      }
   }

   /**
    * Func 账号下线操作
    *
    * @$userInfo['psessionid'] 会话ID
    *
    * @return ['status'=>true|false,'message'=>'']
    */
   public function AccountOffline ( $userInfo = [] )
   {
      if ( empty( $userInfo[ 'psessionid' ] ) )
      {
         return [ 'status' => false , 'message' => '回话ID错误' ];
      }
      $param[ 'psessionid' ]    = (String)$userInfo[ 'psessionid' ];
      $result = Curl::to ( self::$apiUrl . '/controller/campus/v1/portalauth/logout' )
                    ->withHeaders ( [ 'Content-Type: application/json'] )
                    ->withData ( json_encode ( $param ) )
                    ->post ();
      $resultArr = json_decode ( $result , true ); 
      if ( isset($resultArr[ 'errcode' ]) && $resultArr[ 'errcode' ] == '0' )
      {
         return [ 'status' => true , 'message' => '下线成功' ];
      } else {
         return [ 'status' => false , 'message' => '下线失败,请稍后重试' ];
      }
   }

   /**
    * Func 查询账号是否在线
    *
    * @$userInfo['psessionid'] 会话ID
    *
    * @return ['status'=>true|false,'message'=>'']
    */
   public function checkAccountOnline ( $userInfo = [] )
   {
      if ( empty( $userInfo[ 'psessionid' ] ) )
      {
         return [ 'status' => false , 'message' => '回话ID错误' ];
      }
      $result = Curl::to ( self::$apiUrl . "/controller/campus/v1/portalauth/loginstatus/".$userInfo['psessionid'] )
                    ->withHeaders ( [ 'Content-Type: application/json','X-ACCESS-TOKEN:'] )
                    ->get ();
      $resultArr = json_decode ($result,true);
      if(isset($resultArr['errcode']) && $resultArr['errcode'] == '0' && $resultArr['data']['online'] == true )
      {
         return [ 'status' => true , 'message' => '在线' ];
      } else {
         return [ 'status' => false , 'message' => '用户不在线' ];
      }
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