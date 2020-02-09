<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AffichePics extends Model
{
    protected $table = 'affiche_picss';
    protected $fillable = [
        'user_id',
        'iche_id',
        'pics_smallurl',
        'pics_bigurl',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];
}
