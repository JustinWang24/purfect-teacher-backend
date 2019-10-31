<?php

namespace App\Dao\OfficialDocuments;

use App\Models\OfficialDocument\PresetStep;

class PresetStepDao
{

    public function getAllStep($field)
    {
        return PresetStep::select($field)->get();
    }



}
