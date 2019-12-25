<?php

use Illuminate\Contracts\Support\Arrayable;

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


if(!function_exists('pageReturn')){

    /**
     * 分页api统一返回方法
     * @param $result
     * @param $total
     * @param $page
     * @return array
     */

    function pageReturn($result, $total = null , $page = 1){
        if($total){
            $data = [
                'currentPage' => $page,
                'lastPage'    => null,
                'total'       => $total,
                'list'        => $result
            ];
        }
        else{
            $data = [
                'currentPage' => $result->currentPage(),
                'lastPage'    => $result->lastPage(),
                'total'       => $result->total(),
                'list'        => $result->getCollection()
            ];
        }
        return $data;
    }
}
if(!function_exists('outputTranslate')){


    function outputTranslate($data=[],$map=[]){
        if( is_array($data) ){
            try{
                $newData = [];
                foreach ($data as $key=>$value)
                {
                    if (is_array($value) || is_object($value))
                    {
                        $newData[$key] = outputTranslate($value,$map);
                    } else {
                        if (isset($map[$key]))
                        {
                            $newData[$map[$key]] = $value;
                        } else {
                            $newData[$key] = $value;
                        }
                    }
                }
                return $newData;
            }catch (\Exception $exception){
                return $data;
            }
        }elseif (is_object($data)){
            if ($data instanceof Arrayable || is_callable($data, 'toArray')) {
                // 具备转换成数组的条件
                $arr = $data->toArray();
                if (($data instanceof Illuminate\Database\Eloquent\model) && isset($map['_todo'])) {
                    foreach ($map['_todo'] as $key=>$value) {
                        $arr[$key] = $data->$value();
                    }
                }
                return outputTranslate($arr,$map);
            } else {
                // 表明传入的对象, 既没有实现 ArrayAccess, 也没提供 toArray 方法
                throw new \Exception('数据无法正确转换');
            }
        }
        else{
            return $data;
        }
    }
}
