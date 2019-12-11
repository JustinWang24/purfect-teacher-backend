<?php

namespace App\Http\Requests\Notice;

use App\Models\Notices\Notice;
use App\Http\Requests\MyStandardRequest;

class NoticeRequest extends MyStandardRequest
{
    public function getType() {
        return $this->get('type', Notice::TYPE_NOTIFY);
    }

    public function getNoticeId() {
        return $this->get('notice_id',null);
    }
}
