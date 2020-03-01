<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AfficheView extends Model
{
    protected $table = 'affiche_views';
    protected $fillable = [
        'viewsid',
        'user_id',
        'iche_id',
        'created_at',
        'updated_at',
    ];
}
