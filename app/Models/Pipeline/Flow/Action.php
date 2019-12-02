<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use Illuminate\Database\Eloquent\Model;

class Action extends Model implements IAction
{
    public $table = 'pipeline_actions';
    protected $fillable = [
        'flow_id','node_id',
        'result','content','user_id'
    ];

    public function attachments(){
        return $this->hasMany(ActionAttachment::class);
    }
    public function node(){
        return $this->belongsTo(Node::class, 'node_id');
    }
    public function flow(){
        return $this->belongsTo(Flow::class, 'flow_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getNode(): INode
    {
        return $this->node;
    }

    public function getFlow(): IFlow
    {
        return $this->flow;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMessage()
    {
        // TODO: Implement Pipeline Action getMessage() method.
    }

    public function getCode()
    {
        // TODO: Implement Pipeline Action  getCode() method.
    }

    public function getData()
    {
        return $this;
    }

    public function isSuccess()
    {
        return true;
    }
}