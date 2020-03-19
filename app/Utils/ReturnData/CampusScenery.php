<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Utils\ReturnData;

use App\Models\Contents\Album;

class CampusScenery
{
    private $album = [];
    private $videoPath = null;
    private $videoImage = null;
    private $videoTitle = null;
    private $schoolContent = null;
    private $schoolImagePath = null;

    public function __construct($album, $intro)
    {
        $this->album = $album;
        $this->schoolContent = $intro;
    }

    public function toArray(){
        $schoolAlbum = [];
        foreach ($this->album as $album){
            /**
             * @var Album $album
             */
            if($album->isVideo()){
                $this->videoPath = $album->url;
                $this->videoTitle = $album->title;
            } elseif($album->isVideoImage()){
              $this->videoImage = $album->url;
            }else{
                $schoolAlbum[] = [
                    'image_path'=>$album->url,
                    'title'=>$album->title,
                ];
            }
        }

        $data =  [
            'video_path'=>$this->videoPath,
            'video_image'=>$this->videoImage,
            'video_title'=>$this->videoTitle,
            'school_introduce'=>[
                'content'=>$this->schoolContent,
                'img_path'=>$this->schoolImagePath,
            ],
            'school_album'=>$schoolAlbum
        ];

        return $data;
    }
}
