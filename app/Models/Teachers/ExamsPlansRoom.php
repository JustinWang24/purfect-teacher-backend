<?php

namespace App\Models\Teachers;

use App\Models\Schools\Room;
use Illuminate\Database\Eloquent\Model;

class ExamsPlansRoom extends Model
{
    protected  $fillable=[
        'plan_id', 'room_id', 'from', 'to', 'num', 'first_teacher_id', 'first_invigilate',
        'second_teacher_id', 'second_invigilate', 'thirdly_teacher_id', 'thirdly_invigilate'
    ];


    public function room()
    {
        return $this->hasOne(Room::class,'id','room_id');
    }

}
