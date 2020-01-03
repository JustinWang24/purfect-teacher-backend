<?php


namespace App\Models\Evaluate;

use Illuminate\Database\Eloquent\Model;

class EvaluateStudentTitle extends Model
{
    const STATUS_CLOSE = 0;
    const STATUS_START = 1;

    const STATUS_CLOSE_TEXT = '关闭';
    const STATUS_START_TEXT = '开启';

    protected $fillable = ['school_id', 'title', 'status'];

    public static function allStatus() {
        return [
            self::STATUS_CLOSE => self::STATUS_CLOSE_TEXT,
            self::STATUS_START => self::STATUS_START_TEXT,
        ];
    }

    public function statusText() {
        $allStatus = self::allStatus();
        return $allStatus[$this->status];
    }


}
