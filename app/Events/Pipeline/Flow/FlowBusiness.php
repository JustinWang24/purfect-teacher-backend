<?php

namespace App\Events\Pipeline\Flow;

use App\Dao\Pipeline\ActionDao;
use App\Models\Pipeline\Flow\ActionOption;
use App\Models\Pipeline\Flow\Flow;
use App\Utils\Pipeline\IFlow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class FlowBusiness
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flow;
    public $userFlow;

    /**
     * FlowBusiness constructor.
     * @param IFlow|null $flow
     * @param null $userFlow
     */
    public function __construct(IFlow $flow = null, $userFlow = null)
    {
        $this->flow = $flow;
        $this->userFlow = $userFlow;
    }
    public function getFlow()
    {
        return $this->flow;
    }
    public function getUserFlow()
    {
        return $this->userFlow;
    }
    public function getUser()
    {
        return $this->userFlow->user;
    }
    public function getOptions()
    {
        $startUserAction = (new ActionDao())->getFirstActionByUserFlow($this->userFlow->id);
        $node = $this->flow->getHeadNode();
        $business = Flow::getBusiness($this->flow->business);
        $businessOptions = [];
        $titleToUri = [];
        foreach ($business['options'] as $busi) {
            $businessOptions[$busi['uri']] = '';
            $titleToUri[$busi['title']] = $busi['uri'];
        }
        foreach ($node->options as $option) {
            if (isset($titleToUri[$option->title])) {
                $businessOptions[$titleToUri[$option->title]] = ActionOption::where('action_id', $startUserAction->id)->where('option_id', $option->id)->value('value');
            }
        }
        return $businessOptions;
    }

}
