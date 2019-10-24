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
     * @param  String $message
     * @return string
     */
    public static function Success($dataOrMessage=[], $message = 'OK'){
        if(is_array($dataOrMessage)){
            return json_encode([
                'code' => self::CODE_SUCCESS,
                'message' => $message,
                'data' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'code' => self::CODE_SUCCESS,
                'message' => $dataOrMessage,
                'data'=>[]
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
                'code' => self::CODE_ERROR,
                'message' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'code' => self::CODE_ERROR,
                'message' => $dataOrMessage
            ]);
        }
    }
}