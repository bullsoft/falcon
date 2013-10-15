<?php
error_reporting(E_ALL);
try {
    require dirname(__DIR__) . '/apps/Bootstrap.php';
    $boostrap = new Bootstrap();
    $boostrap->execWeb();
} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
