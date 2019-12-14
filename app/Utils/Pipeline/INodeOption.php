<?php
/**
 * 任意步骤要求的执行人必须要填写的额外项目
 */

namespace App\Utils\Pipeline;


interface INodeOption extends IPersistent
{
    const TYPE_TEXT = '文本';
    const TYPE_DATE = '日期';

    public function getNode(): INode;

    public function getName(): string;

    public function getType(): string;
}