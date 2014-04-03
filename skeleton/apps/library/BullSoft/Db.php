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
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$nodes['charset'],
                \PDO::ATTR_TIMEOUT => 3, // seconds
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ),
        );

        $connection = null;
        $try = 1;
        
      RECONNECT:        
        try {
            $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($descriptor);
        } catch(\Exception $e) {
            error_log("PHP Fatal error:  BullSoft::Db::connect() failed to connect to MySQL in " . __FILE__ . " on line " . __LINE__ . '. Detail: ' . json_encode($descriptor));

            while(!is_object($connection)) {
                // wait for 100ms
                usleep(100000);
                error_log('PHP Notice: BullSoft::Db::connnect() retry to connect to MySQL for the ' . $try. ' time ... ');
                if($try++ >= 10) { break; }                
                goto RECONNECT;                
            }
            error_log("PHP Fatal error:  BullSoft::Db::connect() finally failed to connect to MySQL in " . __FILE__ . " on line " . __LINE__ );            
            throw $e;
        }

        $connection->start = time();
        $waitTimeout = $connection->fetchOne("SHOW VARIABLES LIKE 'wait_timeout'", \Phalcon\Db::FETCH_ASSOC);
        $connection->timeout = intval($waitTimeout['Value']);

        // begin debug mode
        $debug = false;
        $logger = null;
        
        if((bool) $configs->application->debug && isset($configs->database->$nodeName->logger)) {
            if(!file_exists($configs->database->$nodeName->logger)) {
                if(mkdir($configs->database->$nodeName->logger, 0777, true)) {
                    $debug = true;
                    $logger = new \Phalcon\Logger\Adapter\File($configs->database->$nodeName->logger . date("Ymd"));
                } else {
                    error_log("PHP Notice:  BullSoft::Db::connect() permission denied for creating directory " . $configs->database->$nodeName->logger);
                }
            } else {
                $debug = true;
                $logger = new \Phalcon\Logger\Adapter\File($configs->database->$nodeName->logger . date("Ymd"));                                    
            }
        }
        // end debug mode
        
        try {
            // event manager
            $evtManager = new \Phalcon\Events\Manager();
            $evtManager->attach('db', function($event, $connection) use ($logger, $debug) {
                if ($event->getType() == 'beforeQuery') {
                    // check timeout to reconnect
                    $idle = time() - $connection->start;
                    if ($idle >= $connection->timeout) {
                        $connection->connect();
                        $connection->start = time();
                    }
                    $sql = $connection->getSQLStatement();
                    // begin debug mode
                    if($debug == true) {
                        $variables = $connection->getSqlVariables();
                        if (count($variables)) {
                            $query = preg_replace("/('.*?')/", "", $sql);
                            $query = preg_replace('/(\?)|(:[0-9a-z_]+)/is', "'%s'", $query);
                            $query = vsprintf($query, $variables);
                            $logger->log($query, \Phalcon\Logger::INFO);
                        } else {
                            $logger->log($sql, \Phalcon\Logger::INFO);
                        }
                    }
                    // end debug mode
                }
            });
            $connection->setEventsManager($evtManager);
        } catch(\Exception $e) {
            // can not throw
            error_log("PHP Notice: BullSoft::Db:connect event attach failed");
        }
        return $connection;
    }
}
