<?php

namespace App\Utils\UI;
use Illuminate\Support\Facades\View;

class WarningMessage extends BasicComponent
{

    /**
     * @param $content 警告内容
     * @param $smSize
     * @param $mdSize
     * @return string
     */
    public static function information($content, $smSize, $mdSize)
    {
        $view = View::make('reusable_elements.section.empty_warning',['content'=> $content, 'smSize' => $smSize, 'mdSize' => $mdSize]);
        echo $view->render();
    }



}
