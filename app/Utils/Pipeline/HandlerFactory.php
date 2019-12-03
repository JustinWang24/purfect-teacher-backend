<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:42 PM
 */

namespace App\Utils\Pipeline;


class HandlerFactory
{
    /**
     * 根据 Node 获取处理器实例
     * @param INode $node
     * @return INodeHandler
     */
    public static function GetHandlerInstance(INode $node){
        /**
         * @var INodeHandler $instance
         */
        $instance = null;
        return $instance;
    }
}