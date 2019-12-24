<?php

namespace App\Console\Commands;

use App\Models\Acl\Role;
use App\Models\Teachers\TeacherProfile;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportTeacherAvatar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:avatar {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入用户照片';

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

        if($type === 'teacher'){
            // 导入教师的照片. 先循环找到所有的老师
            $users = User::select(['id','name'])->where('type',Role::TEACHER)->orderBy('id','desc')->get();
            foreach ($users as $user) {
                // 根据老师的名字, 是否有照片的文件存在
                $avatarFile1 = __DIR__.'/data/teacher_avatars/'.$user->name.'.jpg';
                $avatarFile2 = __DIR__.'/data/teacher_avatars/'.$user->name.'.JPG';

                $from = null;
                if(file_exists($avatarFile1)){
                    $from = $avatarFile1;
                }
                elseif (file_exists($avatarFile2)){
                    $from = $avatarFile2;
                }

                if($from){
                    // 照片找到了
                    $storagePath = storage_path('app/public/users/'.$user->id);
                    if(!is_dir($storagePath)){
                        mkdir($storagePath);
                    }
                    $fileName = Str::random(20).'.jpg';
                    $to = $storagePath.'/'.$fileName;

                    copy($from, $to);
                    $profile = TeacherProfile::where('user_id',$user->id)->first();
                    $profile->avatar = '/storage/users/'.$user->id.'/'.$fileName;
                    $profile->save(); //
                }
            }
        }
    }
}
