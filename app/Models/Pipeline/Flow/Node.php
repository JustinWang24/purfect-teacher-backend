<?php

namespace App\Models\Pipeline\Flow;

use App\Utils\Pipeline\INode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Node extends Model implements INode
{
    use SoftDeletes;
    public $table = 'pipeline_nodes';
    public $timestamps = false;
    protected $fillable = [
        'flow_id','prev_node',
        'next_node','thresh_hold',
        'type','dynamic',
        'name','description',
    ];
    public $casts = ['dynamic'=>'boolean'];

    /**
     * 获取前一个节点
     * @return INode|\Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function prev(){
        if($this->prev_node === 0){
            return null;
        }
        else{
            return $this->hasOne(Node::class, 'prev_node','id');
        }
    }

    /**
     * 获取下一个节点
     * @return INode|\Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function next(){
        if($this->next_node === 0){
            return null;
        }
        else{
            return $this->hasOne(Node::class, 'next_node');
        }
    }

    public function isHead()
    {
        return is_null($this->prev);
    }

    public function isEnd()
    {
        return is_null($this->next);
    }

    public function isDynamic()
    {
        return $this->dynamic;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function flow(){
        return $this->belongsTo(Flow::class,'flow_id');
    }
}
