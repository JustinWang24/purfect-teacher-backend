<?php
if(!function_exists('_printDate')){
    /**
     * 通用的打印日期的方法, 可以方便的修改日期输出的格式, 进行统一管理
     * @param Carbon\Carbon|string $date
     * @return string
     */
    function _printDate($date){
        return $date && is_object($date) ? $date->format('Y年m月d日') : $date;
    }
}

if(!function_exists('_maybeUuid')){
    /**
     * 通用的打印日期的方法, 可以方便的修改日期输出的格式, 进行统一管理
     * @param string $id
     * @return string
     */
    function _maybeUuid($id){
        return is_string($id) && strlen($id) >= 20;
    }
}