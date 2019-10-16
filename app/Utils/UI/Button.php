<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/10/19
 * Time: 1:32 PM
 */

namespace App\Utils\UI;
use Illuminate\Support\Facades\View;

class Button extends BasicComponent
{
    const TYPE_DEFAULT  = 'default ';
    const TYPE_PRIMARY  = 'primary ';
    const TYPE_SUCCESS  = 'success ';
    const TYPE_INFO     = 'info ';
    const TYPE_WARNING  = 'warning ';
    const TYPE_DANGER   = 'danger ';

    /**
     * @param $options
     * @param $type
     * @param bool $textAndIcon 控制按钮文字是否包含 icon, 如果为 true, 表示文字为 $options['icon']; 如果为 false, 文字为 $options['text']; 如果是 string 类型, 表示为需要输出的 icon 类
     * @param bool $codeOnly 是否只返回字符串, 默认为 false
     * @return string
     */
    public static function Print($options, $type, $textAndIcon = false, $codeOnly = false){
        if($textAndIcon === true){
            // 只输出 icon
            $btn = '<button id="'.($options['id']??null).'" class="btn btn-round btn-'.$type.($options['class']??null).'"><i class="fa fa-'.$options['icon'].'"></i></button>';
        }
        elseif($textAndIcon === false){
            // 只输出文字
            $btn = '<button id="'.($options['id']??null).'" class="btn btn-round btn-'.$type.($options['class']??null).'">'.$options['text'].'</button>';
        }
        else{
            // 文字和 icon 一起输出
            $btn = '<button id="'.($options['id']??null).'" class="btn btn-round btn-'.$type.($options['class']??null).'">'.'<i class="fa fa-'.$textAndIcon.'"></i>'.$options['text'].'</button>';
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
        if($textAndIcon === true){
            // 只输出 icon
            $btn = '<button id="'.($options['id']??null).'" class="btn btn-circle btn-'.$type.($options['class']??null).'"><i class="fa fa-'.$options['icon'].'"></i></button>';
        }
        elseif($textAndIcon === false){
            // 只输出文字
            $btn = '<button id="'.($options['id']??null).'" class="btn btn-circle btn-'.$type.($options['class']??null).'">'.$options['text'].'</button>';
        }
        else{
            // 文字和 icon 一起输出
            $btn = '<button id="'.($options['id']??null).'" class="btn btn-circle btn-'.$type.($options['class']??null).'">'.'<i class="fa fa-'.$textAndIcon.'"></i>'.$options['text'].'</button>';
        }

        if($codeOnly){
            return $btn;
        }
        else{
            echo $btn;
        }
    }

    /**
     * 打印按钮组
     * @param $options
     * @param $type
     * @param bool $codeOnly
     * @return string
     */
    public static function PrintGroup($options, $type, $codeOnly = false){
        $options['type'] = $type;
        $view = View::make('reusable_elements.ui.button_group',$options);
        if($codeOnly){
            return $view->render();
        }else{
            echo $view->render();
        }
    }
}