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
        // low and high range values for integer filters
        $range = array(
            'smallint'  => array(pow(-2, 15), pow(+2, 15) - 1),
            'mediumint' => array(pow(-2, 23), pow(+2, 23) - 1),
            'int'       => array(pow(-2, 31), pow(+2, 31) - 1),
            'bigint'    => array(pow(-2, 63), pow(+2, 63) - 1)
        );
        // add filters based on data type
        $elements = array();
        $columns  = (array) $columns;
        
        foreach($columns as $column) {
            $filters  = array();
            switch($column->type) {
                case 'bool':
                    $filters[] = array('validateBool');
                    $filters[] = array('sanitizeBool');
                    break;
                case 'char':
                case 'varchar':
                        $filters[] = array('validateString');
                        $filters[] = array('validateMaxLength', $column->size);
                        $filters[] = array('sanitizeString');
                    break;
                case 'smallint':
                case 'int':
                case 'mediumint':
                case 'bigint':
                    $filters[] = array('validateInt');
                    $filters[] = array('validateRange', $range[$column->type][0], $range[$column->type][1]);
                    $filters[] = array('sanitizeInt');
                    break;
                case 'numeric':
                    $filters[] = array('validateNumeric');
                    $filters[] = array('validateSizeScope', $column->size, $column->scope);
                    $filters[] = array('sanitizeNumeric');
                    break;
                case 'float':
                    $filters[] = array('validateFloat');
                    $filters[] = array('sanitizeFloat');
                    break;
                case 'clob':
                    // no filters, clobs are pretty generic
                    break;
                case 'date':
                    $filters[] = array('validateIsoDate');
                    $filters[] = array('sanitizeIsoDate');
                    break;
                case 'time':
                    $filters[] = array('validateIsoTime');
                    $filters[] = array('sanitizeIsoTime');
                    break;
                case 'timestamp':
                    $filters[] = array('validateIsoTimestamp');
                    $filters[] = array('sanitizeIsoTimestamp');
                    break;
                default :
                    $filters[] = array();
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
