<?php
/* Ip.php --- 
 * 
 * Filename: Ip.php
 * Description: 
 * Author: Gu Weigang, Xiao Xialan
 * Maintainer: 
 * Created: Thu May  9 19:35:29 2013 (+0800)
 * Version: 80815
 * Last-Updated: Wed May 22 13:57:02 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 16
 * 
 */

/* Change Log:
 * 
 * 
 */

/* This program is part of "Baidu Darwin PHP Software"; you can redistribute it and/or
 * modify it under the terms of the Baidu General Private License as
 * published by Baidu Campus.
 * 
 * You should have received a copy of the Baidu General Private License
 * along with this program; see the file COPYING. If not, write to
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The Peaple's
 * Republic of China, 100085.
 */

/* Code: */

namespace Nexus\Library\Geo;

class Ip
{
    protected $ak = null;
    
    const API = "http://api.map.baidu.com/location/ip?ak=%s&ip=%s";

    public function __construct($ak)
    {
        $this->ak = $ak;
    }

    public function getAddress($ip)
    {
        $api = sprintf(self::API, $this->ak, $ip);
        $apiRet = file_get_contents($api);
        if($apiRet == false) {
            throw new \RuntimeException(sprintf(
                "%s", "调用百度IP地理API时发生网络错误： {$api}"
            ));
        }
        $ret = json_decode($apiRet, true);
        if($ret['status'] == 0) {
            return $ret;
        } else {
            throw new \RuntimeException(sprintf(
                "%s", "调用百度IP地理API({$api})失败：".$ret['message']
            ));
        }
    }
}

/* Ip.php ends here */