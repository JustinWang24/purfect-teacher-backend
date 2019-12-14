<?php

namespace App\Models\Pipeline\Flow;

use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IActionOption;
use App\Utils\Pipeline\INodeOption;
use Illuminate\Database\Eloquent\Model;

class ActionOption extends Model implements IActionOption
{
    public $table = 'pipeline_action_options';

    protected $fillable = [
        'action_id','option_id','value'
    ];

    public function action(){
        return $this->belongsTo(Action::class);
    }

    public function nodeOption(){
        return $this->belongsTo(NodeOption::class);
    }

    public function getAction(): IAction
    {
        return $this->action;
    }

    public function getNodeOption(): INodeOption
    {
        return $this->nodeOption;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
