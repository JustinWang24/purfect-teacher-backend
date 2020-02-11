<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AfficheVideo extends Model
{
    protected $table = 'affiche_videos';
    protected $fillable = [
        'user_id',
        'iche_id',
        'cover_url',
        'video_url',
        'status',
        'created_at',
        'updated_at',
    ];
}
