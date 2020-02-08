<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AffichePraise extends Model
{
    protected $table = 'affiche_praises';
    protected $fillable = [
        'typeid',
        'user_id',
        'minx_id',
        'created_at',
    ];
}
