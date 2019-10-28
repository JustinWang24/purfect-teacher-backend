<?php

namespace App\Models\Teachers;

use Illuminate\Database\Eloquent\Model;

class ConferencesMedia extends Model
{
    protected  $table = 'conferences_medias';


    protected  $fillable=[
        'conference_id','media_id'
    ];


    public function media()
    {
        return $this->belongsTo('', 'foreign_key', 'other_key');
    }

}