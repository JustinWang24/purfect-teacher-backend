<?php
/**
 * 对流程中任意一个节点, 都有可能经过多次的反复, 因此也是一对多的关系
 * User: justinwang
 * Date: 2/12/19
 * Time: 12:45 PM
 */

namespace App\Utils\Pipeline;

interface IAction extends IPipelineMessageBag, IPersistent
{
    const RESULT_PENDING = 1; // 等待处理
    const RESULT_NOTICED = 2; // 已阅
    const RESULT_PASS    = 3; // 同意
    const RESULT_REJECT  = 4; // 不同意

    public function getNode(): INode;

    public function getFlow(): IFlow;

    public function getUser(): IUser;

    /**
     * 是否紧急通知下一步的操作人员
     * @return bool
     */
    public function isUrgent():bool ;

    /**
     * 对任意的一个处理, 可能都关联 0 -> N 个附件
     * @return IActionAttachment[]
     */
    public function getAttachments();

    /**
     * @param int $result
     * @return void
     */
    public function setResult($result);

    /**
     * @return int
     */
    public function getResult();

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * 表示动作归属于某个流程的唯一识别号
     * @return string
     */
    public function getTransactionId(): string;
}
