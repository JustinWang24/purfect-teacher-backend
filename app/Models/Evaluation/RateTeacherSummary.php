<?php

namespace App\Models\Evaluation;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RateTeacherSummary extends Model
{
    protected $fillable = [
        'year',
        'term',
        'teacher_id',
        'total_points',
        'timetable_item_id',
        'average_points',
        'total_rates',
        'prepare',
        'material',
        'on_time',
        'positive',
        'result',
        'other',
    ];

    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * 根据传入的新的单条评价计算新的总评数据
     * @param RateTeacherDetail $rateTeacherDetail
     * @return $this
     */
    public function reCalculate(RateTeacherDetail $rateTeacherDetail){
        $this->total_points += $rateTeacherDetail->totalPoints();
        $totalRatesCount = $this->total_rates ?? 0;
        $this->prepare = (($this->prepare * $this->total_rates) + $rateTeacherDetail->prepare)/($totalRatesCount+1);
        $this->material = (($this->material * $this->total_rates) + $rateTeacherDetail->material)/($totalRatesCount+1);
        $this->on_time = (($this->on_time * $this->total_rates) + $rateTeacherDetail->on_time)/($totalRatesCount+1);
        $this->positive = (($this->positive * $this->total_rates) + $rateTeacherDetail->positive)/($totalRatesCount+1);
        $this->result = (($this->result * $this->total_rates) + $rateTeacherDetail->result)/($totalRatesCount+1);

        $totalRatesCount += 1;

        $this->average_points = $this->total_points / $totalRatesCount / 5;
        $this->total_rates = $totalRatesCount;
        return $this;
    }
}
