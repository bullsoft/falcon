<?php
/* Convert.php --- 
 * 
 * Filename: Convert.php
 * Description: 
 * Author: Gu Weigang, Xiao Xialan
 * Maintainer: 
 * Created: Thu May  9 16:29:44 2013 (+0800)
 * Version: 80815
 * Last-Updated: Wed May 22 12:11:29 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 62
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
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The People's
 * Republic of China, 100085.
 */

/* Code: */

namespace \Library\Geo;

class Convert
{
    protected $coordinate = null;

    // x为经度，y为纬度
    const API = "http://api.map.baidu.com/ag/coord/convert?from=%d&to=4&x=%f&y=%f";

    
    public function __construct(Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;
    }

    public function toBDMap()
    {
        switch($this->coordinate->getType()) {
            case Coordinate::MAPBAR :
                $this->mapBar2WGS84();
                $this->WGS842Baidu();
                break;
            case Coordinate::MAP51 :
                $this->map512WGS84();
                $this->WGS842Baidu();
                break;
            case Coordinate::WGS84:
                $this->WGS842Baidu();
                break;
            case Coordinate::GCJ02:
                $this->GCJ022Baidu();
                break;
            default:
                ;
        }
        return $this->coordinate;
    }
    
    public function mapBar2WGS84()
    {
        if($this->coordinate->getType() !== Coordinate::MAPBAR) {
            throw new \InvalidArgumentException(sprintf(
                "%s", "操作的经纬度类型不正确"
            ));
        }
        
        $x = $this->coordinate->getLng();
        $y = $this->coordinate->getLat();
        
        $x = (float)$x*100000%36000000;
        $y = (float)$y*100000%36000000;

        $x1 = (int)(-(((cos($y/100000))*($x/18000))+((sin($x/100000))*($y/9000)))+$x);
        $y1 = (int)(-(((sin($y/100000))*($x/18000))+((cos($x/100000))*($y/9000)))+$y);

        $x2 = (int)(-(((cos($y1/100000))*($x1/18000))+((sin($x1/100000))*($y1/9000)))+$x+(($x>0)?1:-1));
        $y2 = (int)(-(((sin($y1/100000))*($x1/18000))+((cos($x1/100000))*($y1/9000)))+$y+(($y>0)?1:-1));

        $this->coordinate->setLng(floatval($x2/100000.0));
        $this->coordinate->setLat(floatval($y2/100000.0));
        $this->coordinate->setType(Coordinate::WGS84);

        return $this->coordinate;
    }

    public function map512WGS84()
    {
        if($this->coordinate->getType() !== Coordinate::MAP51) {
            throw new \InvalidArgumentException(sprintf(
                "%s", "操作的经纬度类型不正确"
            ));
        }
        $x = $this->coordinate->getLng();
        $y = $this->coordinate->getLat();
        
        $this->coordinate->setLng(floatval($x/10000.0));
        $this->coordinate->setLat(floatval($y/10000.0));
        $this->coordinate->setType(Coordinate::WGS84);
        return $this->coordinate;        
    }

    public function WGS842Baidu()
    {
        if($this->coordinate->getType() !== Coordinate::WGS84) {
            throw new \InvalidArgumentException(sprintf(
                "%s", "操作的经纬度类型不正确"
            ));
        }
        try {
            $api = sprintf(self::API, Coordinate::WGS84, $this->coordinate->getLng(), $this->coordinate->getLat());
            $apiRet = file_get_contents($api);
            if($apiRet==false) {
                throw new \RuntimeException(sprintf(
                    "%s", "请求百度经纬度转换API时发生网络错误: {$api}"
                ));                
            }
            $ret = json_decode($apiRet, true);
            if($ret['error'] == 0) {
                $lng = (float)base64_decode($ret['x']);
                $lat  = (float)base64_decode($ret['y']);
                $this->coordinate->setLng($lng);
                $this->coordinate->setLat($lat);
                $this->coordinate->setType(Coordinate::BDMAP);
                return $this->coordinate;        
            } else {
                throw new \RuntimeException(sprintf(
                    "%s", "请求百度API转换经纬度失败: {$api}"
                ));
            }
        } catch(\Exception $e) {
            error_log($e->getTraceAsString());            
            throw $e;
        }
    }

    public function GCJ022Baidu()
    {
        if($this->coordinate->getType() !== Coordinate::GCJ02) {
            throw new \InvalidArgumentException(sprintf(
                "%s", "操作的经纬度类型不正确"
            ));
        }
        try {
            $api = sprintf(self::API, Coordinate::GCJ02, $this->coordinate->getLng(), $this->coordinate->getLat());
            $apiRet = file_get_contents($api);
            if($apiRet==false) {
                throw new \RuntimeException(sprintf(
                    "%s", "请求百度API时发生网络错误: {$api}"
                ));                
            }            
            $ret = json_decode($apiRet, true);
            if($ret['error'] == 0) {
                $lng = (float)base64_decode($ret['x']);
                $lat  = (float)base64_decode($ret['y']);
                $this->coordinate->setLng($lng);
                $this->coordinate->setLat($lat);
                $this->coordinate->setType(Coordinate::BDMAP);
                return $this->coordinate;        
            } else {
                throw new \RuntimeException(sprintf(
                    "%s", "请求百度API转换经纬度失败: {$api}"
                ));
            }
        } catch(\Exception $e) {
            error_log($e->getTraceAsString());
            throw $e;
        }
    }
}


/* Convert.php ends here */