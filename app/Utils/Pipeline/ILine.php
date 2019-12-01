<?php
/**
 * 审批流程中的流向
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:08 PM
 */

namespace App\Utils\Pipeline;


interface ILine
{
    /**
     * 前一个审批人
     * @return INode
     */
    public function getLeftNode(): INode;

    /**
     * 下一个审批人
     * @return INode
     */
    public function getRightNode(): INode;
}