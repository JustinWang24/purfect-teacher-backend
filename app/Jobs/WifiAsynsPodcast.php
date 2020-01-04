<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WifiAsynsPodcast implements ShouldQueue
{
   use Dispatchable , InteractsWithQueue , Queueable , SerializesModels;

   /**
    * 任务运行的超时时间
    * @var int
    */
   public $timeout = 120;

   // 订单信息
   protected $wifiOrder;
    
	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wifiOrder)
    {
        $this->wifiOrder = $wifiOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       // 订单号错误
       if( !isset($this->wifiOrder->trade_sn) ||  !isset($this->wifiOrder->status))
       {
          // 订单号错误
          return false;
       }
       // 订单状态已处理
       // status 状态(0:关闭,1:待支付,2:支付失败,3:支付成功-WIFI充值中,4:支付成功-WIFI充值成功,5:支付成功-充值失败,6:支付成功-退款成功)
       // mode 类型(1:无线,2:有线)
       if($this->wifiOrder->status != 3 || $this->wifiOrder->mode != 1)
       {
          // 订单还不能处理
          return false;
       }

       // 获取wii
       $wifiUserTimes = DB::table('wifi_user_times')
                          ->where('user_id','=',$this->wifiOrder->user_id)
                          ->first ();

       // 修改时长
       if(isset($wifiUserTimes) && $wifiUserTimes->user_id > 0 )
       {
          $orerWifiTime = intval($this->wifiOrder->order_days * $this->wifiOrder->order_number) * 86400;
          $userWifiTime = strtotime ($wifiUserTimes->user_wifi_time);
          $userWifiTime = $userWifiTime > time() ? $userWifiTime + $orerWifiTime : time() + $orerWifiTime;

          $produce = Factory::produce(1);

          // 提交的数据
          $params['uuid']   =  $wifiUserTimes->user_wifi_uuid;
          $params['mobile']   = $wifiUserTimes->user_wifi_name;
          $params['vaildPeriod']   =  date ('Y-m-d H:i:s',$userWifiTime);
          $addAccount = $produce->editAccount($params);
          if($addAccount['status'] == true)
          {
             // 开启事务
             DB::beginTransaction();

             // 更新时长
             $result1 = DB::table('wifi_user_times')
               ->where('timesid','=',$wifiUserTimes->timesid)
               ->update (['user_wifi_time'=>$params['vaildPeriod']]);

             // 更新订单
             $result2 = DB::table('wifi_orders')
                          ->where('orderid','=',$this->wifiOrder->orderid)
                          ->update (['status'=>4,'succeed_time'=>date('Y-m-d H:i:s')]);
             if($result1 && $result2)
             {
                DB::commit();
             } else {
                DB::rollBack();
             }
          } else {
             // wifi账号添加失败
             return false;
          }
       }else{
          // 注册账号
          $users = DB::table ('users')->where('id','=',$this->wifiOrder->user_id)->first();

          if(!empty($users) && $users->mobile)
          {
             $userWifiTime = time() + intval($this->wifiOrder->order_days * $this->wifiOrder->order_number) * 86400;

             $produce = Factory::produce(1);

             $params[ 'userName' ]    = $users->mobile;
             $params[ 'password' ]    = $users->password;
             $params[ 'password' ]    = 'Test@1234';
             $params[ 'vaildPeriod' ] = date ( 'Y-m-d H:i:s' , $userWifiTime );
             if ( in_array ( $users->type , [ 4 , 5 , 6 , 7 , 8 ] ) )
             {
                $params[ 'userGroupId' ] = env ( 'WIFI_HUAWEI_GROUP_STUDENT' );
             } else {
                $params[ 'userGroupId' ] = env ( 'WIFI_HUAWEI_GROUP_TEACHER' );
             }
             $addAccount = $produce->addAccount($params);

             if($addAccount['status'] == true)
             {
                // 开启事务
                DB::beginTransaction();
                // 更新时长
                $addData[ 'user_id' ]            = $this->wifiOrder->user_id;
                $addData[ 'school_id' ]          = $this->wifiOrder->school_id;
                $addData[ 'campus_id' ]          = $this->wifiOrder->campus_id;
                $addData[ 'user_wifi_time' ]     = $params[ 'vaildPeriod' ];
                $addData[ 'user_wifi_uuid' ]     = $addAccount[ 'data' ][ 'id' ];
                $addData[ 'user_wifi_name' ]     = $users->mobile;
                $addData[ 'user_wifi_password' ] = $params[ 'password' ];

                $result1 = DB::table ( 'wifi_user_times' )->insert ( $addData );

                // 更新订单
                $result2 = DB::table('wifi_orders')
                             ->where('orderid','=',$this->wifiOrder->orderid)
                             ->update (['status'=>4,'succeed_time'=>date('Y-m-d H:i:s')]);
                if($result1 && $result2)
                {
                   DB::commit();
                } else {
                   DB::rollBack();
                }
             } else {
                // wifi账号添加失败
                return false;
             }

          } else {
             // 用户不存在
          }
       }
    }
}
