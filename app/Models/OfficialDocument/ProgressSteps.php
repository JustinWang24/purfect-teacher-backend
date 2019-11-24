<?php

namespace App\Models\OfficialDocument;

use Illuminate\Database\Eloquent\Model;

class ProgressSteps extends Model
{
    protected $fillable = ['progress_id', 'preset_step_id', 'index'];

    /**
     *系统预置步骤表
     */
    public function presetStep()
    {
        return $this->belongsTo(PresetStep::class);
    }

    /**
     * 步骤负载人表
     */
    public function progressStepsUser()
    {
        return $this->hasMany(ProgressStepsUser::class);
    }

}
