<?php

namespace App\Models\Pipeline\Flow;

use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\INodeOption;
use Illuminate\Database\Eloquent\Model;

class NodeOption extends Model implements INodeOption
{
    public $table = 'pipeline_node_options';
    public $timestamps = false;
    protected $fillable = ['node_id','name','type'];

    public function node(){
        return $this->belongsTo(Node::class);
    }

    public function getNode(): INode
    {
        return $this->node;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
