<?php

namespace App\Models\Pipeline\Flow;

use App\Utils\Pipeline\IAction;
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

    public function handler(){
        return $this->hasOne(Handler::class);
    }

    /**
     * 获取前一个节点
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function prev(){
        return $this->hasOne(Node::class, 'id','prev_node')->with('handler');
    }

    /**
     * 获取下一个节点
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function next(){
        return $this->hasOne(Node::class, 'id', 'next_node')->with('handler');
    }

    public function isHead()
    {
        return $this->prev_node === 0;
    }

    public function isEnd()
    {
        return $this->next_node === 0;
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

    public function getLastAction(): IAction
    {
        return Action::where('node_id', $this->id)->orderBy('updated_at','desc')->first();
    }
}
