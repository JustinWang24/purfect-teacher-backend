<?php


namespace App\Models\Account;


use Illuminate\Database\Eloquent\Model;

class AccountCore extends Model
{

    const STATUS_RELEASE = 1;
    const STATUS_UNPUBLISHED = 0;

    const STATUS_RELEASE_TEXT = '已发布';
    const STATUS_UNPUBLISHED_TEXT = '未发布';

}
