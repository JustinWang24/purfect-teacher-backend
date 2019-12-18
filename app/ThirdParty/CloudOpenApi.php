<?php

namespace App\ThirdParty;

use GuzzleHttp\Client;

/**
 * 华三云班牌接口
 */
class CloudOpenApi
{

    const ERROR_CODE_OPEN_API_OK = 0; // 华三接口正常返回code


	public $appId;

	public $appSecret;

	public $timestamp;

    public $apiUrl;

    public $schoolUUid = 'ac446926-7ee4-49d0-88d2-06c7082b6098';

    public function __construct()
    {
        $this->appId     = env('CLOUD_APP_ID');
        $this->appSecret = env('CLOUD_APP_SECRET');
        $this->apiUrl    = env('CLOUD_APP_API');
        $this->timestamp = time();
    }


    /**
     * 计算 AccessKey
     */
    public function accessKey()
    {
        $test = $this->appId. $this->appSecret. $this->timestamp;
        return  md5($test);
    }

    /**
     * @param $uuid
     * @param $imgPath
     * @return string
     */
    public function makePostUploadFaceImg($uuid, $imgPath)
    {

        error_reporting(0);

        $url   = '/open/nva/custom_upload_face_img/';

        $headers = [
            'x-app-id'                => $this->appId,
            'x-access-key'            => $this->accessKey(),
            'x-time-stamp'            => $this->timestamp,
            'X-Custom-Header-3School' => $this->schoolUUid // 学校UUID 后期多个学校可换成传参的形式
        ];

        $client   = new Client;
        $response = $client->request('POST', $this->apiUrl.$url, [
        'headers'   => $headers,
        'multipart' => [
                [
                    'name'     => 'student_id',
                    'contents' => $uuid
                ],
                [
                    'name'     => 'category',
                    'contents' => 'face'
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($imgPath, 'r')
                ]
            ]
        ]);

        return  json_decode($response->getBody()->getContents(), true);
    }
}
