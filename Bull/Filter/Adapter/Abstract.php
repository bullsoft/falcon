<?php
/**
 *
 * 数据过滤器抽象类
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Filter.Adapter
 *
 */

abstract class Bull_Filter_Adapter_Abstract
{
    /**
     *
     * 过滤器管理对象
     *
     * @var \Bull\Filter\Manager
     */
    protected $objManager;

    /**
     *
     * 验证失败表示值
     *
     * @var string
     *
     */
    protected $strInvalid;

    /**
     *
     * 构造器
     *
     * @param \Bull\Filter\Manager
     *
     */
    public function __construct(Bull_Filter_Manager $objManager)
    {
        
        $this->objManager = $objManager;
        $this->resetInvalid();
        $this->postConstruct();
    }

    /**
     *
     * hook for construct
     *
     */
    protected function postConstruct()
    {
        
    }
    
    /**
     *
     * 重置验证失败码，如果不是验证类(Validate*)，验证失败码为null
     *
     * ValidateNotBlank => INVALID_NOT_BLANK
     *
     */
    protected function resetInvalid()
    {
        /* 获取类名最后一部分 */
        $arrParts = explode('_', get_class($this));
        $strName = end($arrParts);

        /* 如果不是验证类(Validate*)，验证失败码为null */
        if (substr($strName, 0, 8) != 'Validate')
        {
            $this->strInvalid = null;
            return;
        }

        /* ValidateNotBlank => invalidNotBlank */
        $strName = 'invalid'. substr($strName, 8);
        
        /* invalidNotBlank => INVALID_NOT_BLANK */
        $strName = strtoupper(preg_replace('/([a-z])([A-Z])/', '$1_$2', $strName));
        
        $this->strInvalid = $strName;
    }

    /**
     *
     * 返回验证失败码
     *
     * @return string
     *
     */
    public function getInvalide()
    {
        return $this->strInvalid;
    }

    /**
     *
     * 设置验证失败码
     *
     * @param string $key
     *
     * @return boolean false
     *
     */
    protected function invalid($key = null)
    {
        if($key)
        {
            $this->strInvalid = $key;
        }
        return false;
    }
}