<?php

namespace App\Models\Pipeline\Flow;

use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Flow extends Model implements IFlow
{
    use SoftDeletes;
    public $table = 'pipeline_flows';
    public $timestamps = false;
    protected $fillable = [
        'school_id','name','type'
    ];

    public function getCurrentPendingNode(): INode
    {
        // TODO: Implement getCurrentPendingNode() method.
    }

    public function setCurrentPendingNode(INode $node)
    {
        // TODO: Implement setCurrentPendingNode() method.
    }
}
