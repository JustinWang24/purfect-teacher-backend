<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/17
 * Time: 上午10:35
 */

namespace App\Models\Notices;


use Illuminate\Database\Eloquent\Model;

class NoticeOrganization extends Model
{
    protected $fillable = ['school_id', 'notice_id', 'organization_id'];

    public $timestamps = false;

}