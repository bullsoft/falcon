<?php
/**
 *
 * 过滤器工厂类
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Filter
 *
 */

class Bull_Filter_Factory
{
    /**
     *
     * 实例化过滤器
     *
     * @param string $strClass
     *
     * @return Bull_Filter_Adpater_Abstract
     *
     */
    public function newInstance($class)
    {
        return new $class(new Bull_Filter_Manager());
    }
}