<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AfficheComment extends Model
{
    protected $table = 'affiche_comments';
    protected $fillable = [
        'iche_type',
        'comment_pid',
        'comment_levid',
        'user_id',
        'user_pics',
        'user_nickname',
        'touser_id',
        'touser_pics',
        'touser_nickname',
        'iche_id',
        'com_content',
        'comment_praise',
        'status',
        'created_at',
        'updated_at',
    ];
}
