<?php


namespace App\Models\Contents;


use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ScienceDao
 * @property int    $school_id
 * @property string $title
 * @property string $content
 * @property int    $media_id
 * @package App\Models\Content
 */
class Science extends Model
{

    use SoftDeletes;

    protected $fillable = ['school_id', 'title', 'content', 'media_id'];


    public function media() {
        return $this->belongsTo(Media::class)->select($this->media_field);
    }
}
