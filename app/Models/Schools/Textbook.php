<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $press
 * @property string $author
 * @property string $edition
 * @property int $course_id
 * @property int $school_id
 * @property boolean $type
 * @property float $purchase_price
 * @property float $price
 * @property string $introduce
 */
class Textbook extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'press', 'author', 'edition', 'course_id', 'school_id', 'type', 'purchase_price', 'price', 'introduce'];

    const TYPE_MAJOR  = 1;
    const TYPE_COMMON = 2;
    const TYPE_SELECT = 3;

    const TYPE_MAJOR_TEXT = '专业教材';
    const TYPE_COMMON_TEXT = '普通教材';
    const TYPE_SELECT_TEXT = '普通教材';
}
