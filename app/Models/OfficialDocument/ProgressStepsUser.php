<?php


namespace App\Models\OfficialDocument;

use Illuminate\Database\Eloquent\Model;

class ProgressStepsUser extends Model
{
     protected $fillable = ['progress_steps_id', 'user_id', 'type'];

}
