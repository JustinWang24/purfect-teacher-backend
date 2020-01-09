<?php


namespace App\Models\Notices;

use Illuminate\Database\Eloquent\Model;

class AppProposalImage extends Model
{

    protected $fillable = ['app_proposal_id', 'path'];

    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/proposal';   // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/proposal';     // 对外的

    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function proposalUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }
}
