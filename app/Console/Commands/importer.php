<?php

namespace App\Console\Commands;

use App\BusinessLogic\ImportExcel\Factory;
use App\Dao\Importer\ImporterDao;
use App\Dao\Users\UserDao;
use Illuminate\Console\Command;

class importer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer {configId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入用户资料';

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
        $this->info('任务开始');
        $optUserId = 1;
        $id = $this->argument('configId');
        $dao = new ImporterDao();
        $taskObj = $dao->getTaskById($id);
        $taskConfig = json_decode($taskObj->config, true);
        $taskConfig['importerName'] = 'App\BusinessLogic\ImportExcel\Impl\\' . $taskConfig['importerName'] . 'Importer';
        $taskConfig['file_path'] = $taskObj->file_path;
        $taskConfig['task_id'] = $id;
        $taskConfig['manager_id'] = $optUserId;
        $obj = Factory::createAdapter($taskConfig);
        $obj->handle();
        $this->info('任务结束');
    }

}
