<?php
error_reporting(E_ALL);
// $debug = new \Phalcon\Debug();
// $debug->listen();
try {
    require dirname(__DIR__) . '/skeleton/apps/Bootstrap.php';
    $boostrap = new Bootstrap();
    $boostrap->execWeb();
} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
