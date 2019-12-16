<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\IUser;
use App\Utils\Pipeline\IUserFlow;
use Illuminate\Database\Eloquent\Model;

class Action extends Model implements IAction
{
    public $table = 'pipeline_actions';
    protected $fillable = [
        'flow_id','node_id',
        'result','content','user_id','urgent',
        'transaction_id', // 标识动作属于同一流程的唯一识别号
    ];

    public $casts = [
        'urgent'=>'boolean'
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
    public function userFlow(){
        return $this->belongsTo(UserFlow::class, 'transaction_id');
    }

    public function options(){
        return $this->hasMany(ActionOption::class);
    }

    public function getNode(): INode
    {
        return $this->node;
    }

    public function getFlow(): IFlow
    {
        return $this->flow;
    }

    public function getUser(): IUser
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
        return $this->result === IAction::RESULT_PASS || $this->result === IAction::RESULT_NOTICED;
    }

    public function isUrgent(): bool
    {
        return $this->urgent;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    public function getUserFlow(): IUserFlow
    {
        return $this->userFlow;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
