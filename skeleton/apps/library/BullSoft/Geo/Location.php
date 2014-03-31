<?php
/* Location.php --- 
 * 
 * Filename: Location.php
 * Description: 
 * Author: Gu Weigang, Xiao Xialan
 * Maintainer: 
 * Created: Thu May  9 20:08:52 2013 (+0800)
 * Version: 80815
 * Last-Updated: Wed May 22 12:19:36 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 23
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

class Location
{
    const LOCATION_API = "http://api.map.baidu.com/geocoder/v2/?ak=%s&output=json&address=%s&city=%s";
    const REVERSE_API  = "http://api.map.baidu.com/geocoder/v2/?ak=%s&location=%f,%f&output=json";

    protected $ak = null;
    
    public function __construct($ak)
    {
        $this->ak = $ak;
    }

    public function getLocation($address, $city = "")
    {
        $api = sprintf(self::LOCATION_API, $this->ak, $address, $city);
        $apiRet = file_get_contents($api);
        if($apiRet == false) {
            throw new \RuntimeException(sprintf(
                "%s", "调用百度地址解析API时发生网络错误: {$api}"
            ));
        }
        $ret = json_decode($apiRet, true);
        if($ret['status'] == 0) {
            return $ret;
        } else {
            throw new \RuntimeException(sprintf(
                "%s", "调用百度地址解析API({$api})失败：".$ret['message']
            ));
        }
    }

    public function getAddress(Coordinate $coordinate)
    {
        if($coordinate->getType() !== Coordinate::BDMAP) {
            throw new InvalidArgumentException(sprintf(
                '%s', '经纬度类型必须是百度'
            ));
        }
        $api = sprintf(self::REVERSE_API, $this->ak, $coordinate->getLat(), $coordinate->getLng());
        $apiRet = file_get_contents($api);
        if($apiRet == false) {
            throw new \RuntimeException(sprintf(
                "%s", "调用百度逆地址解析API时发生网络错误: {$api}"
            ));            
        }
        $ret = json_decode(file_get_contents($api), true);
        if($ret['status'] == 0) {
            return $ret;
        } else {
            throw new \RuntimeException(sprintf(
                "%s", "调用百度逆地址解析API({$api})失败：".$ret['message']
            ));
        }
    }
}

/* Location.php ends here */