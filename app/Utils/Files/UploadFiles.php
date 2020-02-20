<?php

namespace App\Utils\Files;

use App\Dao\NetworkDisk\CategoryDao;
use App\Models\Course;
use App\Models\Courses\Lecture;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use GuzzleHttp\Client;

class UploadFiles
{

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://files.test';
    }

    /**
     * 学生自己的根目录/homework/year/term/course_id/lecture_index/
     *
     * @param Course $course
     * @param Lecture $lecture
     * @param $lectureIdx
     * @param User $student
     * @param $year
     * @param $term
     * @return array
     */
    public function buildStudentHomeworkPath(Course $course, Lecture $lecture, $lectureIdx, User $student, $year = null, $term = null){
        if(!$year){
            $yat = GradeAndYearUtil::GetYearAndTerm(Carbon::now());
            $year = $yat['year'];
            $term = $yat['term'];
        }

        $finalPath = [];
        try{
            $custom = $student->uuid . DIRECTORY_SEPARATOR .
                $year . DIRECTORY_SEPARATOR .
                $term . DIRECTORY_SEPARATOR .
                $course->id . DIRECTORY_SEPARATOR .
                $lecture->id . DIRECTORY_SEPARATOR .
                $lectureIdx;
            $realPath = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'homework'.DIRECTORY_SEPARATOR.$custom);

            $exist = is_dir($realPath);
            if(!$exist){
                mkdir($realPath, 0755, true);
            }

            $finalPath['store_path'] = 'public'.DIRECTORY_SEPARATOR.'homework'.DIRECTORY_SEPARATOR.$custom;
            $finalPath['url_path'] = DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'homework'.DIRECTORY_SEPARATOR.$custom;
        }catch (\Exception $exception){

        }
        return $finalPath;
    }

    /**
     * 上传文件
     * @param $version
     * @param $category
     * @param $user
     * @param $file
     * @param string $keywords
     * @param string $description
     * @return mixed
     */
    public function uploadFile($version, $category, $user, $file, $keywords = '', $description = '')
    {
        $url  = '/api/file/upload';

        if (strlen($keywords) > 1) {
            $data['keywords'] = $keywords;
        }

        if (strlen($description) > 1) {
            $data['description'] = $description;
        }

        $data = [
            'version' => $version,
            'category'=> $category,
            'user'    => $user,
        ];

        return $this->makePostMultipart($url, $data, 'file', $file);
    }

    public function makePostMultipart($uri, $data, $fileFieldName, $filePath)
    {

        $client = new Client();
        $mData = [];

        foreach ($data as $key => $v) {
            $mData[] = [
                'name'=>$key,
                'contents'=>$v
            ];
        }

        $mData[] = ['name' => $fileFieldName, 'contents' =>fopen($filePath,'r')];

        $result =  $client->request(
            'post',
            $this->baseUrl . $uri,
            [
                'multipart' => $mData
            ]
        );

       return json_decode($result->getBody(), true);
    }


}
