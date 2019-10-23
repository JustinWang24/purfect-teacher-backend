<?php

namespace App\Utils\Files;


class UploadFiles
{

    /**
     * 上传文件
     * @param $version
     * @param $category
     * @param $user
     * @param $file
     * @param $keywords
     * @param $description
     */
    public function uploadFile($version, $category, $user, $file, $keywords = '', $description = '')
    {

        $url  = 'https://assets.api.dev.pftytx.com/api/file/upload';

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
            'file'    => $file,
        ];

        $this->curl($url, $data);

    }

    public function curl($url, $data)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_encode($output);
    }


}
