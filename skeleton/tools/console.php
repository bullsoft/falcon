<?php
// php console.php module-name:task-name action-name parameters
error_reporting(E_ALL);
ini_set ("memory_limit", "4G");

try {
    require dirname(__DIR__) . '/apps/Bootstrap.php';
    $bootstrap = new Bootstrap();

    if (floatval(phpversion('phalcon')) < 1.1) {
        $bootstrap->execCli($_SERVER['argv']);
    } else {
        $args = $_SERVER['argv'];

        if(count($args) < 2) {
            throw new \Exception("You must specify module name ***and*** task name");
        }
        array_shift($args);

        $module_task = array_shift($args);
        if(strpos($module_task, ":") === false) {
            throw new \Exception("You must specify module name ***or*** task name");        
        }

        if(($arg = array_shift($args)) !== NULL) {
            $args['action'] = $arg;
        } else {
            $args['action'] = "main";
        }
        list($args['module'], $args['task']) = explode(":", $module_task);    
        $bootstrap->execCli($args);
    }
} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
    echo $e->getTraceAsString();
} catch (PDOException $e){
    echo $e->getMessage();
    echo $e->getTraceAsString();
} catch (\Exception $e) {
    echo $e->getMessage();
    echo $e->getTraceAsString();
}
