<?php

namespace App\Utils\Files;

use GuzzleHttp\Client;

class UploadFiles
{

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://files.test';
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
