<?php

namespace App\Console\Commands;

use App\BusinessLogic\ImportExcel\Factory;
use App\Dao\Importer\ImporterDao;
use App\Dao\Users\UserDao;
use Illuminate\Console\Command;

class importerStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importerStudent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入新生用户资料';

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

        $dao = new ImporterDao();
        $taskObj = $dao->getTasksForNewPlan();
        if (!$taskObj) {
            exit;
        }
        $this->info($taskObj->title.'['.$taskObj->id.']任务开始');
        $taskConfig = json_decode($taskObj->config, true);
        $taskConfig['importerName'] = 'App\BusinessLogic\ImportExcel\Impl\\' . $taskConfig['importerName'] . 'Importer';
        $taskConfig['file_path'] = $taskObj->file_path;
        $taskConfig['task_id'] = $taskObj->id;
        $taskConfig['manager_id'] = 1;
        $obj = Factory::createAdapter($taskConfig);
        $obj->handle();
        $taskObj->status = 2;
        $taskObj->save();
        $this->info($taskObj->title.'['.$taskObj->id.']任务结束');
    }

}
