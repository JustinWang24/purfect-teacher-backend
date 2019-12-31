<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    protected $fillable = [
        'school_id', 'title', 'score'
    ];

}
