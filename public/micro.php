<?php
error_reporting(E_ALL);
try {
    require dirname(__DIR__) . '/skeleton/apps/Bootstrap.php';
    $bootstrap = new Bootstrap();
    $appliction = $bootstrap->execMicro();
    $appliction->handle();
} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}


/* index_micro.php ends here */