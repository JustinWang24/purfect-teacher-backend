<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 9:40 AM
 */

namespace App\Utils;


class JsonBuilder
{
    const CODE_SUCCESS = 1000;
    const CODE_ERROR = 999;
    /**
     * 返回成功JSON消息
     * @param  array|String $dataOrMessage
     * @return string
     */
    public static function Success($dataOrMessage = 'OK'){
        if(is_array($dataOrMessage)){
            return json_encode([
                'error_no' => self::CODE_SUCCESS,
                'data' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'error_no' => self::CODE_SUCCESS,
                'msg' => $dataOrMessage
            ]);
        }
    }

    /**
     * 返回错误JSON消息
     * @param  array|String $dataOrMessage
     * @return string
     */
    public static function Error($dataOrMessage = 'Err'){
        if(is_array($dataOrMessage)){
            return json_encode([
                'error_no' => self::CODE_ERROR,
                'data' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'error_no' => self::CODE_ERROR,
                'msg' => $dataOrMessage
            ]);
        }
    }
}