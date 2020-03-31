<?php

namespace App\Console\Commands;

use App\BusinessLogic\ImportExcel\Factory;
use App\Dao\Importer\ImporterDao;
use Illuminate\Console\Command;

class importerUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:users {importer_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入无专业班级用户';

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
        $id = $this->argument('importer_id');
        $dao = new ImporterDao;
        $data = $dao->getTaskById($id);
        if (is_null($data)) {
            $this->info('未找到任务, 请确定导入任务ID');die;
        }
        if ($data['type'] != 0) {
            $this->info('该导入任务不适用该导入器');die;
        }
        $result = Factory::createAdapter($data->toArray());
        $result->handle();
        $this->info('任务结束');

    }
}
