<?php
declare(strict_types = 1);

namespace app\api\command;

use app\common\services\Lost as LostService;
use think\console\Command;
use think\console\Input;
use think\console\Output;

//失物招领 7日启动结束
class Lost extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('lost')
            ->setDescription('the lost command');
    }

    protected function execute(Input $input, Output $output)
    {
        $service = new LostService();
        while (true) {
            $service->lostCommand();
            sleep(1);
        }
        // 指令输出
        $output->writeln('lost command end');
    }
}
