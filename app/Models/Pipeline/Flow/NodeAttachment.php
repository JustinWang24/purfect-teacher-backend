<?php

namespace App\Models\Pipeline\Flow;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class NodeAttachment extends Model
{
    public $table = 'pipeline_node_attachments';
    public $timestamps = false;
    protected $fillable = [
        'node_id','media_id','url','file_name'
    ];

    public function media(){
        return $this->hasOne(Media::class);
    }
}
