<?php

namespace App\Models\Teachers;

use App\Models\Schools\Room;
use Illuminate\Database\Eloquent\Model;

class ExamsRoom extends Model
{
    protected  $fillable=[
        'exam_id', 'room_id', 'exam_time', 'from', 'to'
    ];


    public function room()
    {
        return $this->hasOne(Room::class,'id','room_id');
    }

}
