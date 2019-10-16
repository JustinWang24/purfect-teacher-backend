<?php
/**
 * 面包屑部分的自动生成
 */
namespace App\Utils\UI;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class Thumbnail extends BasicComponent
{
    /**
     * 输出 Thumbnail 内容
     * @param $pageTitle
     * @param bool $codeOnly
     * @return string
     */
    public static function Print($pageTitle, $codeOnly = false){
        $view = View::make('reusable_elements.section.thumbnail',[
            'pageTitle'=>$pageTitle,
            'words'=>explode('.',Route::currentRouteName())
        ]);
        if($codeOnly){
            return $view->render();
        }else{
            echo $view->render();
        }
    }
}