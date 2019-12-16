<?php

namespace App\Models\Forum;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Community_member extends Model
{
    protected $table = 'communities_member';
    protected $fillable = [
        'school_id', 'community_id', 'user_name', 'reason', 'user_id', 'status'
    ];

    const UNCHECKED = 0;
    const ACCEPT = 1;
    const REJECT = 2;

    const UNCHECKED_TEXT = '未审核';
    const ACCEPT_TEXT = '通过';
    const REJECT_TEXT = '已拒绝';


    public function allStatus() {
        return [
            self::UNCHECKED => self::UNCHECKED_TEXT,
            self::ACCEPT    => self::ACCEPT_TEXT,
            self::REJECT    => self::REJECT_TEXT
        ];
    }

    /**
     * 返回当前状态
     * @return mixed
     */
    public function statusText() {
        $data = $this->allStatus();
        return $data[$this->status];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
