<?php

namespace App\Models\Schools;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

class Organization extends Model
{
    const ROOT = 0;
    protected $fillable = [
        'school_id',
        'name',
        'level',
        'parent_id',
        'phone',
        'description',
        'address',
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function branch(){
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function parent(){
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    /**
     * 组织输出自己的 html 代码, 利用迭代循环输出
     * @return string
     */
    public function output(){
        return '<div class="org '.($this->level > 1 && $this->level < 4 ? 'the-box' : null).'">'
            .View::make('reusable_elements.ui.org'.$this->level,['name'=>$this->name])->render()
            .$this->outputBranch()
            .'</div>';
    }

    /**
     * 组织输出自己的下级组织
     * @return string
     */
    public function outputBranch(){
        $html = '<div class="level-row">';
        foreach ($this->branch as $branch){
            $html .= $branch->output();
        }
        return $html.'</div>';
    }
}
