<?php
/**
 * 对任意的一个处理, 可能都关联 0 -> N 个附件
 * User: justinwang
 * Date: 2/12/19
 * Time: 12:50 PM
 */

namespace App\Utils\Pipeline;

use App\Models\NetworkDisk\Media;

interface IActionAttachment extends IPersistent
{
    public function getAction(): IAction;

    public function getMedia(): Media;

    /**
     * @param $media
     * @return void
     */
    public function setMedia($media);
}