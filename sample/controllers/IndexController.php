<?php
namespace BullSoft\Sample\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $paramArr = $this->dispatcher->getParams();
        // echo "Hello,I'm here: BullSoft\Sample\IndexController" . PHP_EOL;
        // exit;
    }

    public function detailAction()
    {
        
    }
}
