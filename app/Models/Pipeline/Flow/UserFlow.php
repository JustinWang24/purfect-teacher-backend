<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\IUser;
use App\Utils\Pipeline\IUserFlow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFlow extends Model implements IUserFlow
{
    use SoftDeletes;
    public $table = 'pipeline_user_flows';

    protected $fillable = [
        'user_id','flow_id','user_name','done'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function flow(){
        return $this->belongsTo(Flow::class);
    }

    public function actions(){
        return $this->hasMany(Action::class, 'transaction_id','id');
    }

    public function getFlow(): IFlow
    {
        return $this->flow;
    }

    public function getUser(): IUser
    {
        return $this->user;
    }

    /**
     * @return \App\Utils\Pipeline\IAction[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * 是否流程被通过了
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done === IUserFlow::DONE;
    }

    /**
     * 是否流程被否决了
     * @return bool
     */
    public function isTerminated(): bool
    {
        return $this->done === IUserFlow::TERMINATED;
    }
}
