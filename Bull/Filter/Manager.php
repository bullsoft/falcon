<?php
/**
 *
 * 过滤器管理类
 *
 * @author Gu Weignag <guweigang@baidu.com>
 * 
 * @package Bull.Filter
 *
 */

class Bull_Filter_Manager
{
    /**
     *
     * 过滤器链
     *
     * @var array
     *
     */
    protected $arrChainFilters = array();

    /**
     *
     * 验证失败码链
     *
     * @var array
     *
     */
    protected $arrChainInvalid = array();

    /**
     *
     * 验证必须链
     *
     * @var array
     *
     */
    protected $arrChainRequire = array();

    /**
     *
     * 验证白名单链
     *
     * @var array
     *
     */
    protected $arrChainWhitelist = array();

    /**
     *
     * 待验证数据
     *
     * @var array
     *
     */
    protected $arrData;

    /**
     *
     * 当前验证元素名称
     *
     * @var string
     *
     */
    protected $strDataKey;

    /**
     *
     * 当前维持的 过滤器名=>过滤器对象 的映射关系
     * "ValidateNotBlank" => object(Bull_Filter_Adapter_ValidateNotBlank)
     *
     * @var array
     *
     */
    protected $arrFilter = array();

    /**
     *
     * 当前元素是否必需？
     *
     * @var bool
     *
     */
    protected $bolRequire = true;

    /**
     *
     * 释放资源，在unset之前务必先调用此方法
     *
     * @return 
     *
     */

    protected $objLocale;


    public function __construct()
    {
        // @TODO: It should be passed in here
        $locale = new Bull_Util_Locale('zh_CN');
        
        $this->objLocale = $locale;
    }
    
    public function free()
    {
        foreach($this->arrFilter as $key => $val)
        {
            unset($this->arrFilter[$key]);
        }
        unset($this->arrDate);
    }

    /**
     *
     * 动态调用过滤器
     *
     * @param string $strMethod 过滤器名
     *
     * @param array $arrParams 需要验证的数据和条件
     *
     */
    public function __call($strMethod, $arrParams)
    {
        /* validateNotBlank => ValidateNotBlank */
        $objFilter = $this->getFilter(ucfirst($strMethod));
        
        return call_user_func_array($objFilter, $arrParams);
    }

    /**
     *
     * 获取过滤器对象，如果在$this->arrFilter中已存在，则直接返回保持的对象；
     * 如果不存在，则实例化一个
     *
     * @param string $strMethod 验证器名
     *
     * @return Bull_Filter_Adapter_Abstract
     *
     */
    public function getFilter($strMethod)
    {
        if(empty($this->arrFilter[ucfirst($strMethod)]))
        {
            $this->arrFilter[$strMethod] = $this->newFilter($strMethod);
        }
        return $this->arrFilter[$strMethod];
    }

    /**
     *
     * 实例化验证器
     *
     * @param string $strMethod 验证器名
     *
     * @return Bull_Filter_Adapter_Abstract
     *
     */
    public function newFilter($strMethod)
    {
        $strClass = ucfirst($strMethod);
        $objFactory = new Bull_Filter_Factory();
        $objFilter = $objFactory->newInstance("Bull_Filter_Adapter_".$strClass);
        return $objFilter;
    }

    /**
     *
     * 设置元素为必需（不为空）状态
     *
     * @param boolean $bolFlag
     *
     * @return
     *
     */
    public function setRequire($bolFlag)
    {
        $this->bolRequire = (bool) $bolFlag;
    }

    /**
     *
     * 获取当前元素是滞允许为空
     *
     * @return boolean
     *
     */
    public function getRequire()
    {
        return $this->bolRequire;
    }

    /**
     *
     * 批量设置元素不为空
     *
     * @param string $strKey 元素名称
     *
     * @param boolean $bolFlag 是否允许为空
     *
     * @return
     *
     */
    public function setChainRequire($strKey, $bolFlag = true)
    {
        $this->arrChainRequire[$strKey] = (bool) $bolFlag;
    }

    /**
     *
     * 批量设置验证白名单
     *
     * @param array $arrKeys 元素数组
     *
     * @return 
     *
     */
    public function setChainWhitelist($arrKeys)
    {
        if (empty($arrKeys))
        {
            $this->arrChainWhitelist = array();
        } else {
            $this->arrChainWhitelist = (array) $arrKeys;
        }
    }

    /**
     *
     * 添加过滤器链
     *
     * @param string $strKey 元素名称
     *
     * @param mixed $mixSpec 过滤器
     *
     * @return
     *
     */
    public function addChainFilter($strKey, $mixSpec)
    {
        $this->arrChainFilters[$strKey][] = (array) $mixSpec;
    }

    /**
     *
     * 批量添加过滤器链
     *
     * @param string $strKey 元素名称
     *
     * @param array $arrList 过滤器数组
     *
     * @return
     *
     */
    public function addChainFilters($strKey, $arrList)
    {
        foreach((array) $arrList as $mixSpec)
        {
            $this->addChainFilter($strKey, $mixSpec);
        }
    }

    /**
     *
     * 重置过滤器链
     *
     * @param mixed(string | null) $strKey 元素名
     *
     * return
     *
     */
    public function resetChain($strKey = null)
    {
        if ($strKey === null)
        {
            $this->arrChainFilters = array();
            $this->arrChainRequire = array();
            $this->arrChainInvalid = array();
        } else {
            unset($this->arrChainFilters[$strKey]);
            unset($this->arrChainRequire[$strKey]);
            unset($this->arrChainInvalid[$strKey]);
        }
    }

    /**
     *
     * 获取链中验证失败提示
     *
     * @param mixed(string | null) 元素名称
     *
     * @return
     *
     */
    public function getChainInvalid($strKey = null)
    {
        if ($strKey === null)
        {
            return $this->arrChainInvalid;
        } elseif (!empty($this->arrChainInvalid[$strKey])) {
            return $this->arrChainInvalid[$strKey];
        }
    }

    /**
     *
     * 获取元素值
     *
     * @param mixed(string | null) 元素名称
     *
     * @return mixed(null | string | int | double)
     *
     */
    public function getData($strKey = null)
    {
        if ($strKey === null)
        {
            return $this->arrData;
        }
        
        if (isset($this->arrData[$strKey]))
        {
            return $this->arrData[$strKey];
        }
        
        return null;
    }

    /**                                                                         
     *                                                                          
     * 判断在数据数组中是否存在该元素？
     *                                                                          
     * @param string $strKey 元素名称
     *                                                                          
     * @return bool 
     *                                                                          
     */
    public function dataKeyExists($strKey = null)
    {

        if (array_key_exists($strKey, $this->arrData)) {
            return true;
        }

        return false;
    }                                                                           

    /**
     *
     * 设置元素值
     *
     * @param string $strKey 元素名称
     *
     * @param mixed(string | int | double)
     *
     * @return
     *
     */
    public function setData($strKey, $mixVal)
    {
        $this->arrData[$strKey] = $mixVal;
    }

    /**
     *
     * 获取元素名
     *
     * @return string
     *
     */
    public function getDataKey()
    {
        return $this->strDataKey;
    }

    /**
     *
     * 执行验证链
     *
     * @param &array $arrData 元素值数组
     *
     * @return boolean
     */
    public function applyChain(&$arrData)
    {
        /* 保持验证数据 */
        $this->arrData =& $arrData;
        
        $this->arrChainInvalid = array();

        /* 验证非空链 */
        foreach((array) $this->arrChainRequire as $key => $flag)
        {
            /* 允许非空 */
            if (! $flag)
            {
                continue;
            }

            /* 如果有白名单，但不在白名单中 */
            if (! $this->isWhitelisted($key))
            {
                continue;
            }

            /* 如果该元素不存在或元素值为空 ... */
            $bolBlank = ! isset($this->arrData[$key]) ||
                $this->ValidateBlank($this->arrData[$key]);
            
            /* ... 则设置验证失败值 */
            if ($bolBlank)
            {
                $this->arrChainInvalid[$key][] = $this->objLocale->get("INVALID_NOT_BLANK", __CLASS__);
            }
        }

        /* 验证过滤器链 */
        $arrKeys = array_keys($this->arrChainFilters);
        foreach($arrKeys as $key)
        {
            /* 如果已设置验证失败值 */
            if (!empty($this->arrChainInvalid[$key]))
            {
                continue;
            }

            /* 如果有白名单，但不在白名单中 */
            if (! $this->isWhitelisted($key))
            {
                continue;
            }

            /* 设置当前元素名称 */
            $this->strDataKey = $key;
            
            if (! empty($this->arrChainRequire[$key]))
            {
                /* 元素要求非空 */
                $this->setRequire(true);
            } else {
                /* 元素允许为空 */
                $this->setRequire(false);
                if (! isset($this->arrData[$key]))
                {
                    continue;
                }
            }
            
            /* 验证设置在元素上的过滤器 */
            foreach((array) $this->arrChainFilters[$key] as $arrParams)
            {
                /* 获取验证器名 */
                $strMethod = array_shift($arrParams);

                /* 保持除验证器名外的其他参数 */
                $arrLocaleParams = $arrParams;

                /* 把元素值填充到参数数组中 */
                array_unshift($arrParams, $this->arrData[$key]);

                /* 执行验证 */
                $mixResult = $this->__call($strMethod, $arrParams);

                /* 过滤器类型：验证还是清理 */
                $strType = strtolower(substr($strMethod, 0, 8));

                if ($strType == 'sanitize')
                {
                    /* 如果是清理，则修改元素值 */
                    $this->arrData[$key] = $mixResult;
                } elseif ($strType == 'validate' && ! $mixResult) {
                    /* 如果是验证，且结果为假，则设置验证失败提示 */
                    $strInvalid = $this->getFilter($strMethod)->getInvalide();
                    
                    $this->arrChainInvalid[$key][] = $this->objLocale->get($strInvalid, __CLASS__,
                                                                           $arrLocaleParams);
                }
            }
        }
        
        /* 判断验证失败数组是否为空 */
        $bolResult = empty($this->arrChainInvalid);
        
        /* return boolean */
        return $bolResult;
    }

    /**
     *
     * 白名单判断，如果未设置，则直接返回true
     *
     * @param string $strKey 元素名称
     *
     * @return boolean
     *
     */
    public function isWhitelisted($strKey)
    {
        if (! $this->arrChainWhitelist)
        {
            return true;
        } else {
            return in_array($strKey, $this->arrChainWhitelist);
        }
    }
}