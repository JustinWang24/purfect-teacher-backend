<?php
/**
 * 复杂的步骤: 它处理那些从多个方向进入, 或者下一步去多个方向的节点
 * User: justinwang
 * Date: 2/12/19
 * Time: 12:40 AM
 */

namespace App\Utils\Pipeline;

interface IComplexNode extends INode
{
    /**
     * 获取左侧的汇入流程线集合
     * @return ILine[]
     */
    public function getPrevLines();

    /**
     * 获取右侧的流出流程线集合
     * @return ILine[]
     */
    public function getNextLines();
}