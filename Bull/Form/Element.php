<?php
/**
 *
 * Element Class for Bull
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Form
 *
 */
class Bull_Form_Element
{
    /**
     *
     * 元素数组
     *
     * @var array
     *
     * {{code: php
     *     $this->arrElements[$key] = array(
     *         'value'   => null,
     *         'status'  => null,
     *         'require' => false,
     *         'filters' => array(),
     *         'invalid' => array(),               
     *     );
     * }}
     */
    public $arrElements = array();

    /**
     *
     * 过滤器管理对象
     *
     * @var Bull_Filter_Manager
     *
     */
    protected $objFilterManager;

    /**
     *
     * 元素验证结果
     *
     * @var boolean
     *
     */
    protected $bolStatus = null;

    /**
     *
     * 构造器，初始化过滤器管理对象
     *
     */
    public function __construct()
    {        
        $this->objFilterManager = new Bull_Filter_Manager();
    }

    /**
     *
     * 添加过滤器
     *
     * @param string $strName 元素名称
     *
     * @param mixed $mixSpec 过滤器详情
     *
     * @return 
     */
    public function addFilter($strName, $mixSpec)
    {
        $arrSpec = (array) $mixSpec;
        $this->arrElements[$strName]['filters'][] = $arrSpec;
        if (lcfirst($arrSpec[0]) == 'validateNotBlank')
        {
            $this->arrElements[$strName]['require'] = true;
        }
        return $this;
    }

    /**
     *
     * 批量添加过滤器
     *
     * @param string $strName 元素名称
     *
     * @param array $arrList 过滤器数组
     *
     * @return
     *
     */
    public function addFilters($strName, $arrList)
    {
        foreach((array) $arrList as $mixSpec)
        {
            $this->addFilter($strName, $mixSpec);
        }
        return $this;
    }

    /**
     *
     * 设置验证失败提示
     *
     * @param string $strName 元素名称
     *
     * @parm mixed(string | array) 验证失败提示
     *
     * @return
     *
     */
    public function addInvalid($strName, $mixSpec)
    {
        $this->setStatus(false);
        
        foreach((array) $mixSpec as $strText)
        {
            $this->arrElements[$strName]['invalid'][] = $strText;
            $this->arrElements[$strName]['status'] = false;
        }
        return $this;
    }

    /**
     *
     * 批量设置验证失败提示
     *
     * @param array $arrList 失败提示数组
     *
     * @return
     * 
     */
    public function addInvalids($arrList)
    {
        foreach((array) $arrList as $name => $spec)
        {
            $this->addInvalid($name, $spec);
        }
        return $this;
    }

    /**
     *
     * 获取验证失败提示
     * 当$strName为NULL时，获取所有提示;否则获取指定元素的验证失败提示
     *
     * @param mixed(string | null) $strName 元素名称
     *
     * @return mixed(array | null)
     *
     */
    public function getInvalids($strName = null)
    {
        if ($strName !== null)
        {
            if (isset($this->arrElements[$strName]))
            {
                return $this->arrElements[$strName]['invalid'];
            } else {
                return null;
            }
        }

        $arrInvalids = array();
        foreach($this->arrElements as $key => $element)
        {
            $arrInvalids[$key] = $element['invalid'];
        }
        return $arrInvalids;

    }
    
    /**
     *
     * 设置元素
     *
     * @param string $strName 元素名称
     *
     * @param array $arrInfo  元素内容
     *
     */
    public function setElement($strName, $arrInfo = array())
    {
        /* 默认元素属性 */
        $arrDefaultElement = array(
            'value'   => null,
            'status'  => null,
            'require' => false,
            'filters' => array(),
            'invalid' => array(),               
        );

        /* 填充默认值 */
        $arrElement = array_merge($arrDefaultElement, (array) $arrInfo);

        /* 类型转换 */
        $arrElement['value']   =         $arrElement['value']; /* mixed value */
        $arrElement['require'] = (bool)  $arrElement['require'];
        $arrElement['filters'] = (array) $arrElement['filters'];
        $arrElement['invalid'] = (array) $arrElement['invalid'];
        
        /* 生成元素 */
        $this->arrElements[$strName] = $arrElement;

        return $this;
    }

    /**
     *
     * 批量设置元素
     *
     * @param array $arrList 元素数组
     * {{code: php
     *     $arrList = array(
     *         'username" => array(
     *                 "value"   => "guweigang",
     *                 "require" => true, 
     *         ),
     *         'age' => array(
     *                 "value"    => 27,
     *                 "filters" => array("ValidateInt", array("ValidateMax", 100), array("ValidateMin", 1)),
     *         ),
     *         // more ...
     *     );
     * }}
     * 
     * @return
     *
     */
    public function setElements($arrList)
    {
        foreach((array) $arrList as $name => $info)
        {
            $this->setElement($name, $info);
        }

        return $this;
    }

    /**
     *
     * 设置元素的值
     *
     * @param string $strName 元素名称
     *
     * @param mixed $mixValue 元素值(value)
     *
     * @return
     *
     */
    public function setValue($strName, $mixValue)
    {
        if (! empty($this->arrElements[$strName]))
        {
            $this->arrElements[$strName]['value'] = $mixValue;
        }
        
        return $this;
    }

    /**
     *
     * 批量设置元素的值
     *
     * @param array $arrSpec 元素值的数组
     * {{code: php
     *    $arrSpec = array(
     *        'username' => 'guweigang',
     *        'age'      => 27,
     *        // more ...
     *    );
     * }}
     *
     * @return
     *
     */
    public function setValues($arrSpec)
    {
        foreach($this->arrElements as $name => &$element)
        {
            if(is_array($arrSpec) && isset($arrSpec[$name]))
            {
                $element['value'] = $arrSpec[$name];
            }
        }

        return $this;
    }

    /**
     *
     * 设置元素组的验证状态
     *
     * @param boolean $bolStatus 验证状态，有一个元素验证失败即为false
     *
     * @return
     *
     */
    public function setStatus($bolStatus)
    {
        if ($bolStatus !== true && $bolStatus !== false && $bolStatus !== null)
        {
            throw new Exception("Not Allowed Parameter");
        }
        
        if ($bolStatus === $this->bolStatus)
        {
            return;
        }
        
        $this->bolStatus = (bool) $bolStatus;

        return $this;
    }

    /**
     *
     * 获取元素组验证状态
     *
     * @return boolean
     *
     */
    public function getStatus()
    {
        return $this->bolStatus;
    }

    /**
     *
     * 执行验证操作
     *
     * @return boolean
     *
     */
    public function validate()
    {
        /* 重置过滤器链 */
        $this->objFilterManager->resetChain();

        /* 元素值的数组 */
        $arrData = array();

        /* 设置过滤器，过滤条件 */
        foreach($this->arrElements as $name => &$info)
        {
            $arrData[$name] = &$info['value'];
            $this->objFilterManager->addChainFilters($name, $info['filters']);
            $this->objFilterManager->setChainRequire($name, $info['require']);
        }

        /* 链式验证 */
        $bolStatus = $this->objFilterManager->applyChain($arrData);

        /* 设置验证结果 */
        $this->setStatus($bolStatus);

        /* 获取验证失败提示 */
        $arrInvalid = $this->objFilterManager->getChainInvalid();

        /* 添加验证失败提示 */
        foreach((array) $arrInvalid as $key => $val)
        {
            $this->addInvalid($key, $val);
        }

        // 返回验证结果
        // return $bolStatus;
        return $this;
    }

    /**
     *
     * 验证是否成功
     *
     * @return boolean
     *
     */
    public function isSuccess()
    {
        return $this->bolStatus === true;
    }

    /**
     *
     * 验证是否失败
     *
     * @return boolean
     *
     */
    public function isFailure()
    {
        return $this->bolStatus === false;
    }
}
