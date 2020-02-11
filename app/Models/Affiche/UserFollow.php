<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    protected $table = 'user_follows';
    protected $fillable = [
        'user_id',
        'touser_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
