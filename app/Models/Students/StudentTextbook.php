<?php


namespace App\Models\Students;


use App\Models\Schools\Textbook;
use Illuminate\Database\Eloquent\Model;

class StudentTextbook extends Model
{
    protected $fillable = ['user_id', 'textbook_id', 'year'];

    public $updated_at = false;


    public function textbook() {
        return $this->belongsTo(Textbook::class);
    }


}
