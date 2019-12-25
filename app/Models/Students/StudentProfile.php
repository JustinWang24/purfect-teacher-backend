<?php

namespace App\Models\Students;

use App\Models\RecruitStudent\RegistrationInformatics;
use App\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Model;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Illuminate\Support\Facades\Log;

class StudentProfile extends Model
{
    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/avatar/';    // 存放用户头像路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/avatar/';     // 对外的

    const GENDER_MAN   = 1;
    const GENDER_WOMAN = 0;
    const GENDER_MAN_TEXT   = '男';
    const GENDER_WOMAN_TEXT = '女';

    const DRIVER_LOCAL    = 1; // 本地
    const DRIVER_ALI_YUN  = 2;
    const DRIVER_QI_NIU   = 3;

    protected $fillable = [
        'uuid',
        'user_id',
        'device',
        'year', // 招生年度
        'serial_number', // 录取编号
        'gender',
        'country',
        'state',
        'city',
        'postcode',
        'area', // 地区名称
        'address_line',
        'address_in_school',
        'student_number', // 考生号
        'license_number', // 准考证号
        'id_number', // 身份证号
        'birthday',
        'avatar',
        'qq',
        'wx',
        'examination_score', // 中高考分数
        'source_place',     // 生源地
        'political_code', // 政治面貌代码
        'political_name', // 政治面貌名称
        'nation_code', // 民族代码
        'nation_name', // 民族名称
        'parent_name', // 家长姓名
        'parent_mobile', // 家长手机号
        'source',// 统招还是自招
        'qr_code_url', // 统招还是自招
        'create_file', //
        'special_support', //
        'very_poor', //
        'disability', //
        'resident_type', //
        'resident_suburb', //
        'resident_village', //
        'comments', //
    ];

    public $dates = ['birthday'];

    public function getQrCode(){
        if(is_null($this->qr_code_url)){
            // 如果是空的, 那么就生成一个, 然后返回生成的 url. Code 是有 user 的 uuid, 以及其他的内容而生成的
            try{
                $qrContent = [
                    'user'=>[
                        'uuid'=>$this->user->uuid,
                        'name'=>$this->user->name,
                    ]
                ];
                $qrCode = new QrCode(json_encode($qrContent));
                $qrCode->setSize(300);

                // Set advanced options
                $qrCode->setWriterByName('png');
                $qrCode->setMargin(10);
                $qrCode->setEncoding('UTF-8');
                $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
                $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
                $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
//                $qrCode->setLabel($this->user->name, 16, null, LabelAlignment::CENTER());
//                $qrCode->setLogoPath($this->getAvatarAttribute());
//                $qrCode->setLogoSize(150, 150);
                $qrCode->setRoundBlockSize(true);
                $qrCode->setValidateResult(false);
                $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
                $folder = 'users/'.$this->user_id.'/profile/qrcode';
                if(!is_dir(storage_path('app/public/'.$folder))){
                    mkdir(storage_path('app/public/'.$folder),0777, true);
                }
                $path = $folder.'/p_qr.png';
                $qrCode->writeFile(storage_path('app/public/'.$path));
                $this->qr_code_url = '/storage/'.$path;
                $this->save();
            }catch (\Exception $exception){
                Log::critical('用户唯一二维码生成错误',['msg'=>$exception->getMessage(),'id'=>$this->user_id]);
            }
        }
        return $this->qr_code_url;
    }

    public function user()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function registrationInformatics()
    {
        return $this->hasMany(RegistrationInformatics::class,'user_id', 'user_id');
    }

    public function getAvatarAttribute($value){
        return asset(empty($value) ? User::DEFAULT_USER_AVATAR : $value);
    }

    /**
     * 获取性别
     * @return mixed
     */
    public function getGenderTextAttribute() {
        $sex = [
            self::GENDER_MAN => self::GENDER_MAN_TEXT,
            self::GENDER_WOMAN => self::GENDER_WOMAN_TEXT,
        ];
        return $sex["{$this->gender}"];
    }


    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function avatarUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }
}
