<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 11/12/19
 * Time: 11:11 PM
 */

namespace App\Utils\Time;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CalendarWeek implements Arrayable
{
    /**
     * @var boolean 是否是当前周
     */
    private $current;
    private $name;
    /**
     * @var Carbon
     */
    private $start;
    /**
     * @var Carbon
     */
    private $end;
    /**
     * @var Collection
     */
    private $events;

    public function __construct($name, $start, $end, $events = null)
    {
        $this->name = $name;
        $this->start = Carbon::createFromFormat('Y-m-d',$start);
        $this->end = Carbon::createFromFormat('Y-m-d',$end);
        $this->events = $events ?? new Collection();
        $now = Carbon::now();
        $this->current = $now->between($this->start, $this->end);
    }

    /**
     * 是否给定日期是本周的某一天
     * @param Carbon $date
     * @return bool
     */
    public function includes(Carbon $date){
        return $date->between($this->start, $this->end);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Carbon
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return Carbon
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return Collection
     */
    public function getEvents(){
        return $this->events;
    }

    /**
     * @param $events
     */
    public function setEvents($events){
        $this->events = $events;
    }

    public function toArray()
    {
        return [
            'current'=>$this->current ? 1 : 0,
            'name'=>$this->name,
            'start'=>$this->start->format('Y-m-d'),
            'end'=>$this->end->format('Y-m-d'),
//            'events'=>$this->events,
        ];
    }
}