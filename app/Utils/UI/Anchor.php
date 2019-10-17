<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/10/19
 * Time: 9:34 PM
 */

namespace App\Utils\UI;

class Anchor extends BasicComponent
{
    /**
     * @param $options
     * @param $type
     * @param bool $textAndIcon 控制按钮文字是否包含 icon, 如果为 true, 表示文字为 $options['icon']; 如果为 false, 文字为 $options['text']; 如果是 string 类型, 表示为需要输出的 icon 类
     * @param bool $codeOnly 是否只返回字符串, 默认为 false
     * @return string
     */
    public static function Print($options, $type, $textAndIcon = false, $codeOnly = false){
        $href = $options['href'] ?? '#';

        if(isset($options['params'])){
            $href .= '?'.http_build_query($options['params']);
        }

        if($textAndIcon === true){
            // 只输出 icon
            $btn = '<a href="'.$href.'" id="'.($options['id']??null).'" class="btn btn-round btn-'.$type.($options['class']??null).'"><i class="fa fa-'.$options['icon'].'"></i></a>';
        }
        elseif($textAndIcon === false){
            // 只输出文字
            $btn = '<a href="'.$href.'" id="'.($options['id']??null).'" class="btn btn-round btn-'.$type.($options['class']??null).'">'.$options['text'].'</a>';
        }
        else{
            // 文字和 icon 一起输出
            $btn = '<a href="'.$href.'" id="'.($options['id']??null).'" class="btn btn-round btn-'.$type.($options['class']??null).'">'.'<i class="fa fa-'.$textAndIcon.'"></i>'.$options['text'].'</a>';
        }

        if($codeOnly){
            return $btn;
        }
        else{
            echo $btn;
        }
    }

    /**
     * @param $options
     * @param $type
     * @param bool $textAndIcon 控制按钮文字是否包含 icon, 如果为 true, 表示文字为 $options['icon']; 如果为 false, 文字为 $options['text']; 如果是 string 类型, 表示为需要输出的 icon 类
     * @param bool $codeOnly 是否只返回字符串, 默认为 false
     * @return string
     */
    public static function PrintCircle($options, $type, $textAndIcon = false, $codeOnly = false){
        $href = $options['href'] ?? '#';

        if(isset($options['params'])){
            $href .= '?'.http_build_query($options['params']);
        }

        if($textAndIcon === true){
            // 只输出 icon
            $btn = '<a href="'.$href.'" id="'.($options['id']??null).'" class="btn btn-circle btn-'.$type.($options['class']??null).'"><i class="fa fa-'.$options['icon'].'"></i></a>';
        }
        elseif($textAndIcon === false){
            // 只输出文字
            $btn = '<a href="'.$href.'" id="'.($options['id']??null).'" class="btn btn-circle btn-'.$type.($options['class']??null).'">'.$options['text'].'</a>';
        }
        else{
            // 文字和 icon 一起输出
            $btn = '<a href="'.$href.'" id="'.($options['id']??null).'" class="btn btn-circle btn-'.$type.($options['class']??null).'">'.'<i class="fa fa-'.$textAndIcon.'"></i>'.$options['text'].'</a>';
        }

        if($codeOnly){
            return $btn;
        }
        else{
            echo $btn;
        }
    }
}