<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\INodeHandler;
use Illuminate\Database\Eloquent\Model;

class Handler extends Model implements INodeHandler
{
    public $timestamps = false;
    public $table = 'pipeline_handlers';
    public $fillable = [
        'node_id',
        'user_id',
        'organization_id',
        'title_id',
    ];

    public function user(){
        if($this->user_id === 0){
            return null;
        }
        else{
            return $this->belongsTo(User::class);
        }
    }

    public function node(){
        return $this->belongsTo(Node::class,'node_id');
    }

    public function handle(INode $node): IAction
    {
        // TODO: Implement handle() method.
    }

    public function resume(INode $node): IAction
    {
        // TODO: Implement resume() method.
    }
}
