<?php

namespace App\Models\Forum;


use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class ForumType extends Model
{
    protected $fillable = ['school_id', 'title', 'type'];

    const TYPE_FORUM = 1;
    const TYPE_TEAM = 2;

    const TYPE_FORUM_TEXT = '论坛';
    const TYPE_TEAM_TEXT = '社团';


    public static function type()
    {
        return [
            self::TYPE_FORUM => self::TYPE_FORUM_TEXT,
            self::TYPE_TEAM  => self::TYPE_TEAM_TEXT,
        ];
    }

    public function getTypeName()
    {
        $data = $this->type();
        return $data[$this->type];
    }

    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }


}

