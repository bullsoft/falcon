<?php
error_reporting(E_ALL);
try {
    require dirname(__DIR__) . '/apps/Bootstrap.php';
    $bootstrap = new Bootstrap();
    $bootstrap->setConfig();
    $runEnv = $bootstrap->config->application->runEnv;
    switch($runEnv)
    {
        case 'Module':
            $bootstrap->execWeb();
            break;
        case 'Micro':
            $bootstrap->execMicro();
            break;
    }

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
