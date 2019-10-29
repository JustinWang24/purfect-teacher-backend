<?php

namespace App\Models\Timetable;

use App\Models\Misc\Enquiry;
use Illuminate\Database\Eloquent\Model;

class TimetableItemEnquiry extends Model
{
    protected $fillable = [
        'timetable_item_id',
        'enquiry_id',
        'scheduled_at',
        'end_at',
    ];

    public $timestamps = false;
    public $casts = ['scheduled_at'=>'datetime','end_at'=>'datetime'];

    public function timetableItem(){
        return $this->belongsTo(TimetableItem::class);
    }

    public function enquiry(){
        return $this->belongsTo(Enquiry::class);
    }
}
