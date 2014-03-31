<?php
/* Coordinate.php --- 
 * 
 * Filename: Coordinate.php
 * Description: 
 * Author: Gu Weigang, Xiao Xialan
 * Maintainer: 
 * Created: Thu May  9 16:13:44 2013 (+0800)
 * Version: 80320
 * Last-Updated: Mon May 13 10:55:57 2013 (+0800)
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
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The Peaple's
 * Republic of China, 100085.
 */

/* Code: */

namespace Nexus\Library\Geo;

class Coordinate
{
    // GPS
    const WGS84 =  0;
    // Google Map, Soso Map, Sogou Map
    const GCJ02 =  2;
    // baidu
    const BDMAP =  4;
    // 51 Map
    const MAP51 =  1;
    // Map Bar
    const MAPBAR = 3;

    // 纬度
    protected $lat;

    // 经度
    protected $lng;

    // 经纬度类型
    protected $type;

    // 中国纬度
    const CHINA_MIN_LAT = 3.52;
    const CHINA_MAX_LAT = 53.33;

    // 中国经度
    const CHINA_MIN_LNG = 73.40;
    const CHINA_MAX_LNG = 135.2;
    
    public function __construct(array $coordinates, $type)
    {
        if(is_array($coordinates) && count($coordinates) == 2) {
            $x = 0.0;
            $y = 0.0;
            if(isset($coordinates['lat']) && isset($coordinates['lng'])) {
                $x = $coordinates['lng'];
                $y = $coordinates['lat'];
            } else {
                $x = $coordinates[1];
                $y = $coordinates[0];
            }
            if($y < self::CHINA_MIN_LAT || $y > self::CHINA_MAX_LAT) {
                throw new \InvalidArgumentException(sprintf(
                    '%s', '文盲，中国纬度范围：' . self::CHINA_MIN_LNG. '~' . self::CHINA_MAX_LNG
                ));
            }
            if($x < self::CHINA_MIN_LNG || $x > self::CHINA_MAX_LNG) {
                throw new \InvalidArgumentException(sprintf(
                    '%s', '文盲，中国经度范围：' . self::CHINA_MIN_LAT . '~' . self::CHINA_MAX_LAT
                ));
            }
            $this->setLat($y);
            $this->setLng($x);
        } else {
            throw new \InvalidArgumentException(sprintf(
                '%s', '必须是经纬度的数组，array(经度，纬度)'
            ));
        }
        $this->setType($type);
    }

    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $type = intval($type);
        if ($type < self::WGS84 || $type > self::BDMAP) {
            throw new InvalidArgumentException(sprintf(
                '%s', '经纬度类型必须是'.self::WGS84. '~'. self::BDMAP . '之间'
            ));
        }        
        $this->type = $type;
    }
    
    public function toArray($assoc = true)
    {
        if($assoc) {
            return array(
                'lat' => $this->getLat(),
                'lng' => $this->getLng(),
            );
        } else {
            return array(
                $this->getLat(),
                $this->getLng(),
            );
        }
    }
}

/* Coordinate.php ends here */