<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    const TYPE_NEWS = 1; // 类型为动态, 未来可以移植为科研 等其他类型

    protected $fillable = [
        'school_id','title','publish'
    ];

    public $casts = [
        'publish'=>'boolean'
    ];

    public function sections(){
        return $this->hasMany(NewsSection::class);
    }
}
