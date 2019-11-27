<?php


namespace App\Models\Students;


use Illuminate\Database\Eloquent\Model;

class StudentTextbook extends Model
{
    protected $fillable = ['user_id', 'textbook_id'];

    public $updated_at = false;

}
