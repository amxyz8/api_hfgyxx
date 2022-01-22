<?php
declare(strict_types = 1);

namespace app\api\command;

use app\common\services\Lottery as LotteryService;
use think\console\Command;
use think\console\Input;
use think\console\Output;

//抽奖 结束时间之后自动结束
class Lottery extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('lottery')
            ->setDescription('the lottery command');
    }

    protected function execute(Input $input, Output $output)
    {
        $service = new LotteryService();
        while (true) {
            $service->lotteryCommand();
            sleep(1);
        }
        // 指令输出
        $output->writeln('lottery command end');
    }
}
