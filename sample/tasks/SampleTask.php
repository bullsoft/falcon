<?php
namespace BullSoft\Sample\Tasks;

class SampleTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
        echo <<<EOT
+---------------------------------------------------+
|              Congratulation !                     |
|                                                   |
| You are here: BullSoft\Sample\Tasks\SampleTask;   |
|                                                   |
+---------------------------------------------------+
EOT;
        echo PHP_EOL;
    }
}
