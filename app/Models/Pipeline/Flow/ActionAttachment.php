<?php

namespace App\Models\Pipeline\Flow;

use App\Models\NetworkDisk\Media;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IActionAttachment;
use Illuminate\Database\Eloquent\Model;

class ActionAttachment extends Model implements IActionAttachment
{
    public $table = 'pipeline_action_attachments';
    public $timestamps = false;
    protected $fillable = [
        'action_id','media_id','url'
    ];

    public function media(){
        return $this->belongsTo(Media::class);
    }
    public function action(){
        return $this->belongsTo(Action::class,'action_id');
    }

    public function getAction(): IAction
    {
        return $this->action;
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function setMedia($media)
    {
        if(is_object($media)){
            $this->media_id = $media->id;
            $this->url = $media->url;
            $this->save();
        }
        else{
            $mediaModel = Media::find($media);
            if($mediaModel){
                $this->media_id = $media;
                $this->url = $mediaModel->url;
                $this->save();
            }
        }
    }
}
