<?php


namespace App\Models\Users;


use App\User;
use App\Models\Schools\Facility;
use Illuminate\Database\Eloquent\Model;

class UserCodeRecord extends Model
{

    protected $fillable = [
        'user_id', 'school_id', 'facility_id', 'type', 'desc'
    ];

    const TYPE_VALIDATION = 1;
    const TYPE_SPENDING   = 2;

    const TYPE_VALIDATION_TEXT = '验证';
    const TYPE_SPENDING_TEXT   = '消费';


    public function user(){
        return $this->belongsTo(User::class);
    }


    public function facility() {
        return $this->belongsTo(Facility::class);
    }


    public function allType() {
        return [
            self::TYPE_VALIDATION => self::TYPE_VALIDATION_TEXT,
            self::TYPE_SPENDING   => self::TYPE_SPENDING_TEXT,
        ];
    }


    public function typeText() {
        $data = $this->allType();
        return $data[$this->type]??'';
    }
}
