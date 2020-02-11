<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class BbsMessage extends Model
{
    protected $table = 'bbs_messages';
    protected $fillable = [
        'mess_type1',
        'mess_type2',
        'mess_type3',
        'mess_mixid',
        'mess_content',
        'mess_content1',
        'mess_pics',
        'mess_commentid',
        'user_id',
        'user_pics',
        'user_nickname',
        'touser_id',
        'touser_pics',
        'touser_nickname',
        'touser_is_read',
        'autherids',
        'mess_content2',
        'status',
        'created_at',
        'updated_at',
    ];
}
