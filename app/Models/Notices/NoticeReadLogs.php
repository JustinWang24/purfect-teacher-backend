<?php


namespace App\Models\Notices;


use Illuminate\Database\Eloquent\Model;

class NoticeReadLogs extends Model
{
    protected $fillable = ['notice_id', 'user_id'];

    protected $table = 'notice_read_logs';

    public $updated_at = false;
}
