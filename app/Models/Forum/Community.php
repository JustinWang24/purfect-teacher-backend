<?php

namespace App\Models\Forum;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $table = 'communities';
    protected $fillable = [
        'school_id', 'name', 'detail', 'logo', 'pic1', 'pic2', 'pic3',
        'user_id', 'status', 'forum_type_id'
    ];

    const STATUS_UNCHECKED = 0;
    const STATUS_CHECK = 1;
    const STATUS_CLOSE = 2;

    const STATUS_UNCHECKED_TEXT = '未审核';
    const STATUS_CHECK_TEXT = '已审核';
    const STATUS_CLOSE_TEXT = '已拒绝';


    public function allStatus() {
        return [
            self::STATUS_UNCHECKED => self::STATUS_UNCHECKED_TEXT,
            self::STATUS_CHECK     => self::STATUS_CHECK_TEXT,
            self::STATUS_CLOSE     => self::STATUS_CLOSE_TEXT,
        ];
    }

    public function getStatusText() {
        $data = $this->allStatus();
        return $data[$this->status];
    }


    public function member()
    {
        return $this->hasMany(Community_member::class, 'community_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school() {
        return $this->belongsTo(School::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
