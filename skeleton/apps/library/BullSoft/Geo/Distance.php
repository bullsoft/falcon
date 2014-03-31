<?php
/* Distance.php --- 
 * 
 * Filename: Distance.php
 * Description: 
 * Author: Gu Weigang, Xiao Xialan
 * Maintainer: 
 * Created: Thu May  9 18:15:53 2013 (+0800)
 * Version: master
 * Last-Updated: Mon Mar 31 13:43:08 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 17
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

namespace BullSoft\Geo;

class Distance
{
    protected $from = null;
    
    protected $to = null;

    const EARTH_RADIUS = 6378137;
    
    public function __construct(Coordinate $from, Coordinate $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    // distance in meters
    public function caculate()
    {
        if($this->from->getType() !== $this->to->getType()) {
            throw new \InvalidArgumentException(sprintf(
                "%s", "操作的经纬度类型不匹配"
            ));            
        }
        $lat1 = deg2rad($this->from->getLat());
        $lng1 = deg2rad($this->from->getLng());
        
        $lat2 = deg2rad($this->to->getLat());
        $lng2 = deg2rad($this->to->getLng());
        
        $a = $lat1- $lat2;
        $b = $lng1 - $lng2;

        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($lat1)*cos($lat2)*pow(sin($b/2),2)));
        
        $s = $s * self::EARTH_RADIUS;
        
        $s = round($s * 10000) / 10000;
        
        return $s;
    }
}

/* Distance.php ends here */