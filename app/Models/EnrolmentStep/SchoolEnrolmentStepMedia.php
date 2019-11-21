<?php

namespace App\Models\EnrolmentStep;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_enrolment_step_id
 * @property int $media_id
 */
class SchoolEnrolmentStepMedia extends Model
{

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_enrolment_step_medias';

    /**
     * @var array
     */
    protected $fillable = ['school_enrolment_step_id', 'media_id'];

    public $media_field = ['url'];


    public function media() {
        return $this->belongsTo(Media::class)->select($this->media_field);
    }

}
