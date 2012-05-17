<?php
/**
 *
 * 数据库模型过滤器
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Model
 *
 */

class Bull_Model_Filter
{
    /**
     *
     * 元素对象
     *
     * @var Bull_Form_Element
     *
     */
    protected $element;
    
    public function __construct(Bull_Form_Element $element)
    {
        $this->element = $element;
    }

    public function newFilter($columns)
    {
        $elements = array();
        $columns  = (array) $columns;
        foreach($columns as $column)
        {
            switch($column->type) {
                case "char":
                case "varchar":
                    $filters = array('ValidateString',
                                     array('ValidateRangeLength', 1, $column->size));
                    break;
                case "tinyint":
                    $filters = array('ValidateBool');
                    break;
                case "int":
                case "bigint":
                    $filters = array('ValidateInt');
                    break;
                case "numeric":
                    $filters = array('ValidateNumeric');
                    break;
                case "float":
                    $filters = array('ValidateFloat');
                    break;
                case "date":
                    $filters = array('ValidateIsoDate');
                    break;
                case "time":
                    $filters = array('ValidateIsoTime');
                    break;
                case "timestamp":
                    $filters = array('ValidateIsoTimestamp');
                    break;
                default :
                    $filters = array();
            }
            
            $elements[$column->name] = array(
                "filters" => (array) $filters,
                "require" => $column->primary ? false: (bool)  $column->notnull,
            );
            
        }
        
        $this->element->setElements($elements);
        
        return $this->element;
    }
}
