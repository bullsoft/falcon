<?php
/**
 *
 * 全局本地字符类，其他本地字符类需继续此类 (PHP_VERSION > 5.3.0)
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Util
 *
 */

final class Bull_Util_Locale
{
    public $lang   = "zh_CN";

    public function __construct($lang="")
    {
        $lang = trim($lang);
        if (!empty($lang)) {
            $this->lang = $lang;            
        }
    }
    
    /**
     *
     * 获取本地字符，如$replace不为空，则替换本地字符串中的格式符
     *
     * Notice: 静态延迟绑定 self绑定到父类，static绑定到子类本身
     *
     * @param string $key 键值
     *
     * @param array $replace 替换数组
     *
     * @param string $class 类名
     *
     */
    public function get($key="", $class="", $replace=array())
    {
        $class = empty($class) ?  __CLASS__ : $class;
        $stack = array();
        $stack[] = 'Bull_Util';
        
        if ($class != __CLASS__) {
            array_unshift($stack, $class);
        }

        foreach($stack as $name) {
            $dir  = str_replace("_", DIRECTORY_SEPARATOR, $name);
            $file = dirname($dir) . DIRECTORY_SEPARATOR
                . 'Locale' . DIRECTORY_SEPARATOR . $this->lang . ".php";

            $obj  = new Bull_Parse_Php();
            $locale   = $obj->load($file)->get('locale');
        
            /* 如果不存在相关的映射，则返回$key */
            if (!isset($locale[$key])) {
                continue;
            } else {
                /* 如果替换数组不为空，则替换相关映射中的格式符 */
                if (!empty($replace)) {
                    return vsprintf($locale[$key], $replace);
                } else {
                    /* return */
                    return $locale[$key];
                }
            }
        }
        return $key;
    }
}