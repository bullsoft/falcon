<?php
namespace BullSoft;
class Db
{
    static public function connect($nodeName)
    {
        $configs = getDI()->get('config');
        $nodes = $configs->database->$nodeName->toArray();
        $totalNodes = $configs->database->$nodeName->nodes;
        $nodeIds = range(1, intval($totalNodes));
        $nodeId = array_rand($nodeIds);
        $descriptor = array(
            "host"     => $nodes['host'][$nodeId],
            "port"     => $nodes['port'][$nodeId],
            "username" => $nodes['username'][$nodeId],
            "password" => $nodes['password'][$nodeId],
            "dbname"   => $nodes['dbname'][$nodeId],
            "options"  => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$nodes['charset']
            ),
        );
        try {
            $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($descriptor);
        } catch(\Exception $e) {
            error_log("PHP Fatal error:  Nexus::Db::connect() failed to connect to MySQL Host in " . __FILE__ . " on line " . __LINE__);
            usleep(200000);
            $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($descriptor);            
        }
        // debug mode
        if((bool) $configs->application->debug &&
           isset($configs->database->$nodeName->logger)
        ) {
            if(!file_exists($configs->database->$nodeName->logger)) {
                try {
                    mkdir($configs->database->$nodeName->logger, 0777, true);
                } catch(\Exception $e) {
                    error_log("Db.php: permission denied for creating directory");
                }
            }
            try {
                // event manager
                $evtManager = new \Phalcon\Events\Manager();
                $logger = new \Phalcon\Logger\Adapter\File($configs->database->$nodeName->logger . date("Ymd"));
                $evtManager->attach('db', function($event, $connection) use ($logger) {
                    if ($event->getType() == 'beforeQuery') {
                        $variables = $connection->getSqlVariables();
                        if (count($variables)) {
                            $logger->log($connection->getSQLStatement() . "; Bind parameters: ". join(", ", $variables), \Phalcon\Logger::INFO);
                        } else {
                            $logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);                            
                        }
                    }
                });
                $connection->setEventsManager($evtManager);
            } catch(\Exception $e) {
                // can not throw
                error_log("Db.php: event attach error");
            }
        }
        return $connection;
    }
}
