<?php
declare(strict_types = 1);

namespace app\command;

use app\common\services\News;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class NewsSync extends Command
{
    protected function configure()
    {
        //设置名称为task
        $this->setName('news_sync')
            //增加一个命令参数
            ->addArgument('action', Argument::OPTIONAL, "action", '')
            ->addArgument('force', Argument::OPTIONAL, "force", '')
            ->setDescription('the news_sync command');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取输入参数
        $action = trim($input->getArgument('action'));
        $force = trim($input->getArgument('force'));

        // 配置任务，每隔20秒访问2次网站
        $task = new \EasyTask\Task();
        $task->setRunTimePath($this->app->getRuntimePath());
        $task->addFunc(function () {
//            $date = date('H:i:s');
            $date = date('i');
            if ($date != "00") {
                return;
            }
            //Write your code
            (new News())->newsSync1();
            (new News())->newsSync2();
            (new News())->newsSync3();
            (new News())->newsSync4();
        }, 'request', 1, 1);

        // 根据命令执行
        if ($action == 'start') {
            $task->start();
        } elseif ($action == 'status') {
            $task->status();
        } elseif ($action == 'stop') {
            $force = ($force == 'force'); //是否强制停止
            $task->stop($force);
        } else {
            exit('Command is not exist');
        }
    }
}
