<?php

namespace App\Console\Commands;

use App\Models\Acl\Role;
use App\Models\Teachers\TeacherProfile;
use App\Models\Users\GradeUser;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use App\User;
use Ramsey\Uuid\Uuid;

class TeacherDataImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:teachers {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入教师数据';

    protected $reader = null;
    protected $cols = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type = $this->argument('type');

        $filePath = '';

        try{
            if($type === 'lixian'){
                // 导入礼县教师
                $filePath = __DIR__.'/data/lixian-teacher.xlsx';
                $this->lixianImporter($filePath);
            }
        }
        catch (\Exception $exception){
            dd($exception->getMessage());
        }
    }

    /**
     * @param $file
     * @throws \Exception
     */
    protected function lixianImporter($file){
        $this->reader = IOFactory::createReader('Xlsx');
        $this->reader->setReadDataOnly(true);
        $this->reader->setLoadSheetsOnly('Sheet1');
        $spreadsheet = $this->reader->load($file);

        $sheet = $spreadsheet->getSheet(0);

        $startColumn = 'B';
        $endColumn = $sheet->getHighestColumn();

        $this->cols = [
            'B',// 0
            'C',// 1
            'D',// 2
            'E',// 3
            'F',// 4
            'G',// 5
            'H',// 6
            'I',// 7
            'J',// 8
            'K',// 9
            'L',// 10
            'M',// 11
            'N',// 12
            'O',// 13
            'P',// 14
            'Q',// 15
            'R',// 16
            'S',// 17
        ];

        $startRow = 2;
        $endRow = $sheet->getHighestDataRow();
        $iter = $sheet->getRowIterator($startRow, $endRow);
        foreach (range($startRow, $endRow) as $rowIndex) {
            $dataToSave = [];
            foreach ($this->cols as $colIndex) {
                $cell = $sheet->getCell($colIndex.$rowIndex);
                $dataToSave[] = $cell->getValue();
            }
            $this->save($dataToSave);
            $iter->next();
        }
    }

    protected function save($data){
        if(empty(trim($data[0]))){
            return;
        }

        $u = User::where('mobile',substr($data[2],10))->first();
        if($u){
            echo $u->name .' 账户已存在'.PHP_EOL;
            $gu = GradeUser::where('user_id',$u->id)->first();
            if(!$gu){
                $gu = new GradeUser();
                $gu->user_id = $u->id;
                $gu->name = $u->name;
                $gu->user_type = Role::TEACHER;
                $gu->school_id = 1;
                $gu->campus_id = 1;
                $gu->institute_id = 508;
                $gu->department_id = 0;
                $gu->grade_id = 0;
                $gu->last_updated_by = 1;
                $gu->save();
                echo 'GU added'.PHP_EOL;
            }
        }

        DB::beginTransaction();
        try{
            $user = new User();
            if(!$u){
                $user->uuid = Uuid::uuid4()->toString();
                $user->password = Hash::make(substr($data[2],-6)); // 身份证号后 6 位作为密码
                $user->api_token = Uuid::uuid4()->toString();
                $user->name = $data[0];
                $user->mobile = substr($data[2],-8); // 身份证号后 8 位作为手机号
                $user->type = Role::TEACHER;
                $user->status = User::STATUS_VERIFIED;
                $user->save();
            }
            else{
                $user = $u;
            }

            if(true){
                $joinAt = null;
                if(!empty(trim($data[14]))){
                    $str = str_replace('中二','',$data[14]);
                    $str = str_replace('小一','',$str);
                    $arr = explode('.',$str);
                    $joinAt = Carbon::createFromFormat('Y-m-d',$arr[0].'-'.$arr[1].'-01');
                }

                $profile = TeacherProfile::where('user_id',$user->id)->first();
                if(!$profile){
                    $profile = new TeacherProfile();
                    $profile->uuid = Uuid::uuid4()->toString();
                    $profile->school_id = 1;
                }

                $profile->user_id = $user->id;
                $profile->serial_number = substr($data[2],10);
                $profile->group_name = $data[8]??'n.a';
                $profile->education = $data[7]??null;
                $profile->joined_at = $joinAt ? $joinAt->format('Y-m-d'):null;
                $profile->title = $data[11]??null;
                $profile->gender = $data[1] === '男' ? 1 : 2;
                $profile->id_number = $data[2];
                $profile->birthday = GradeAndYearUtil::IdNumberToBirthday($data[2])->getData();
                $profile->avatar = User::DEFAULT_USER_AVATAR;

                $profile->work_start_at = $data[5]??null;
                $profile->major = $data[8]??null;
                $profile->final_education = $data[9]??null;
                $profile->final_major = $data[10]??null;
                $profile->title_start_at = $data[12]??null;
                $profile->title1_at = $data[15]??null;
                $profile->title1_hired_at = $data[16]??null;
                $profile->hired_at = $data[14]??null;
                $profile->hired = $data[13]=='是'?true:false;
                $profile->notes = $data[17]??null;
                $profile->save();
            }
            DB::commit();
            echo $user->name .' 账户创建'.PHP_EOL;
        }
        catch (\Exception $exception){
            DB::rollBack();
            dd($exception->getMessage());
        }
        return;
    }
}
