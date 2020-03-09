<?php

namespace App\Console\Commands;

use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use Illuminate\Console\Command;

class dissolvedElectiveCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dissolvedElectiveCourse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取消不满足人数的选修课';

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
        $dao = new TeacherApplyElectiveCourseDao();
        $list = $dao->gettoDissolvedElectiveList();
        if ($list) {
            foreach ($list as $item) {
                $enrollCount = $dao->getEnrolledTotalForCourses($item->course_id);
                if ($enrollCount < $item->open_num) {
                    $dao->discolved($item->course_id);
                }
            }
        }
    }
}
