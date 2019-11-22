<?php

namespace App\ThirdParty;

use GuzzleHttp\Client;

// +----------------------------------------------------------------------
// | JuhePHP [ NO ZUO NO DIE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2015 http://juhe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Juhedata <info@juhe.cn>
// +----------------------------------------------------------------------
//----------------------------------
// 万年历调用示例代码 － 聚合数据
// 在线接口文档：http://www.juhe.cn/docs/177
// 获取当天的详细信息 指定日期,格式为YYYY-MM-DD,如月份和日期小于10,则取个位,如:2012-1-1
// 获取当月的详细信息 指定月份,格式为YYYY-MM,如月份和日期小于10,则取个位,如:2012-1
// 获取当年的详细信息 指定年份,格式为YYYY,如:2015
//----------------------------------

class CalendarApi
{
    const  GET_DAY_INFO   = 1; // 当天日历信息
    const  GET_MONTH_INFO = 2; // 当月日历信息
    const  GET_YEAR_INFO  = 3; // 当年日历信息

    private $appKey;
    private $url;
    private $param;
    private $suffix;

    public function __construct()
    {
        $this->appKey = " ";
        $this->url    = "http://japi.juhe.cn/calendar/";
        $this->param  = null;
    }

    public function getCalendarInfo($type, $time)
    {
        if ($type == self::GET_DAY_INFO) {

            $this->suffix = 'day';
            $this->param  = 'date';

        } elseif ($type == self::GET_MONTH_INFO) {

            $this->suffix = 'month';
            $this->param  = 'year-month';

        } elseif ($type == self::GET_YEAR_INFO) {

            $this->suffix = 'year';
            $this->param  = 'year';

        } else {
            return false;
        }

        $url = $this->url . $this->suffix;

        $params = [
            'key'         => $this->appkey,
            $this->suffix => $time,
        ];

        $result = $this->makePostApi($url, $params);
        if ($result) {
            if ($result['error_code'] == '0') {
                print_r($result);
            } else {
                echo $result['error_code'] . ":" . $result['reason'];
            }
        } else {
            echo "请求失败";
        }
    }


    public function makePostApi($uri, $data)
    {
        $client = new Client();
        $result = $client->request(
            'post',
            $this->baseUrl . $uri,
            [
                'multipart' => $data
            ]
        );
        return json_decode($result->getBody(), true);
    }


}
