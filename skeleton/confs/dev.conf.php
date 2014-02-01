<?php
return new \Phalcon\Config(array(
    "application" => array(
        "modelsDir" => "/apps/common/models/",
        "viewsDir" => "/apps/common/views/",
        "pluginsDir" => "/apps/plugins/",
        "libraryDir" => "/apps/library/",
        "baseUri" => "/sample/",
        "baseUrl" => "http://dev.falcon.com/",
        "logger" => array(
            "dir" => "/home/work/var/log/skeleton/",
            "format" => "[%file%:%line%][%ip%] %message%",
        ),
        "debug" => false,
        "defaultModule" => "sample",
    ),
    "library" => array()
));