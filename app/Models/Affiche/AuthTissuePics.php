<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class AuthTissuePics extends Model
{
    protected $table = 'auth_tissue_pics';
    protected $fillable = [
        'user_id',
        'tissue_id',
        'pics_small',
        'pics_big',
        'status',
        'created_at',
        'updated_at',
    ];
}
