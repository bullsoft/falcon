<?php
/* Geo.php --- 
 * 
 * Filename: Geo.php
 * Description: 
 * Author: Gu Weigang, Xiao Xialan
 * Maintainer: 
 * Created: Thu May  9 16:12:30 2013 (+0800)
 * Version: master
 * Last-Updated: Mon Mar 31 13:43:16 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 14
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

class Geo
{
    public function ip($ak)
    {
        return new Ip($ak);
    }

    public function coordinate(array $coordinates, $type)
    {
        return new Coordinate($coordinates, $type);
    }

    public function distance(Coordinate $c1, Coordinate $c2)
    {
        return new Distance($c1, $c2);
    }

    public function location($ak)
    {
        return new Location($ak);
    }

    public function convert(Coordinate $c)
    {
        return new Convert($c);
    }
}

/* Geo.php ends here */