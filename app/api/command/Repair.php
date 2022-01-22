<?php
declare(strict_types = 1);

namespace app\api\command;

use app\common\services\Repair as RepairService;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Repair extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('repair')
            ->setDescription('the repair command');
    }

    protected function execute(Input $input, Output $output)
    {
        $repairService = new RepairService();
        while (true) {
            $repairService->testCommond();
            sleep(1);
        }
        // 指令输出
        $output->writeln('repair123');
    }
}
