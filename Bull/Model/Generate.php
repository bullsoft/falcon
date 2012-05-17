<?php
/**
 *
 * 模型生成类
 *
 * @author: Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Model
 *
 */

class Bull_Model_Generate
{
    /**
     *
     * SQL对象
     *
     * @var Bull_Sql_Adapter_Abstract
     *
     */
    protected $sql;

    /**
     *
     * 数据库配置节名
     *
     * @var string
     *
     */
    protected $name;

    /**
     *
     * 系统目录
     *
     * @var string
     *
     */
    protected $system;

    /**
     *
     * 模型目录
     *
     * @var string
     *
     */
    protected $model_dir;
    
    public function __construct($name, $sql, $system, $model_dir)
    {
        $this->name = $name;
        $this->sql = $sql;
        $this->system = $system;
        $this->model_dir = $model_dir;
    }
    
    public function execute($table, $model)
    {
        if ('*' == $table)
        {
            $tables = $this->sql->fetchTableList();
            $model = null;
            
            foreach($tables as $tbl)
            {
                $this->code($tbl, $model);
            }
            
        } else {
            $this->code($table, $model);
        }
    }

    protected function code($tbl, $model)
    {
        $model_name = $model ? $model : join("", array_map("ucfirst", explode("_", $tbl)));
        
        // db => Db, it will generate  models with Db_ prefix in dir "models/Db"
        // dbtemp =>Dbtemp, it will generate models with Dbtemp_ prefix in dir "models/Dbtemp"
        $model_prefix = ucfirst(strtolower($this->name));
        
        $prefix11 = str_repeat(" ", 11);
        $prefix09 = str_repeat(" ", 9);
        
        $cols  = $this->sql->fetchTableCols($tbl);
        
        $cols_code = var_export($cols, true).";";
        
        $cols_code = str_replace(array("Bull_Sql_Column::", ")),", ");"),
                                 array($prefix11. "Bull_Sql_Column::", $prefix11. ")),", $prefix09.");"),
                                 $cols_code);
        
        $cols_code = preg_replace('/(\'\w+\' =>\s*)/', $prefix11. "$1", $cols_code);
        $cols_code = preg_replace('/(\'\w+\' => \S+)/', " $1", $cols_code);

        $root_dir   = $this->system . DIRECTORY_SEPARATOR;
        $model_dir  = $this->model_dir;
        
        $class_prefix = str_replace(DIRECTORY_SEPARATOR, "_", substr($model_dir, strlen($root_dir)));
        
        $dir = $model_dir . DIRECTORY_SEPARATOR . $model_prefix;
        
        $code = <<<EOT
<?php
class {$class_prefix}_{$model_prefix}_{$model_name} extends Bull_Model_Abstract
{
     protected \$table = "{$tbl}";

     protected \$name  = "{$this->name}";

     protected function postConstruct()      
     {
         \$this->cols = {$cols_code}
     }
}
EOT;
        if (!file_exists($dir))
        {
            try {
                mkdir($dir, 0777, true);
            } catch (Bull_Model_Exception $e) {
                error_log($e->getMessage());
            }
        }
        $fhander = fopen($dir. DIRECTORY_SEPARATOR . $model_name.".php", "w+");
        fwrite($fhander, $code);
        fclose($fhander);
    }
}
