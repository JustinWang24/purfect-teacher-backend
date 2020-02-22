<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class AfficheStick extends Model
{
    protected $table = 'affiche_sticks';
    protected $fillable = [
        'stickid',
        'school_id',
        'stick_posit',
        'stick_title',
        'stick_mixid',
        'stick_order',
        'status',
        'created_at',
        'updated_at',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school() {
        return $this->belongsTo(School::class,'school_id','id');
    }
}
