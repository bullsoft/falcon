<?php
return new Phalcon\Config(array(
    'application'=>array(
        'runEnv' => 'Module',
        'debug' => true,
        "baseUri" => "/sample/",
        "baseUrl" => "http://dev.falcon.com/",
        'logger'=> array(
            'dir' => PHALCON_DIR.'/sample/var/logs/',
            'format' => "[%file%:%line%][%ip%] %message%",
        ),
    ),
    'view' => array(
        'compiledPath' => PHALCON_DIR.'/sample/var/cache/view/',
        'compiledExtension' => '.compiled',
    ),

    "web_module" => array(
        'sample' => array(
            "className" => "BullSoft\\Sample\\Module",
            "path" => PHALCON_DIR."/sample/Module.php"
        )
    ),
    "cli_module" => array(
        'sample' => array(
            "className" => "BullSoft\\Sample\\Task",
            "path" => PHALCON_DIR."/sample/Task.php"
        )
    ),
    "database"=>array(
            "db" => array(
            "nodes" => 2,
            "charset" => "utf8",
            "host" => array(
                "10.48.31.126",
                "10.48.31.126"
            ),
            "port" => array(
                "8006",
                "8006"
            ),
            "username" => array(
                "root",
                "root"
            ),
            "password" => array(
                "root",
                "root"
            ),
            "dbname" => array(
                "Vs_Exp",
                "Vs_Exp"
            )
        )
    )
));
?>