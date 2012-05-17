<?php
/**
 *
 * 数据库模型抽象类
 *
 * 由于是Framework空间下所有模型的继承类，
 * 所以严重依赖Bull_Parse_Ini和Bull_Di_Container。
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Model
 *
 */

abstract class Bull_Model_Abstract extends Bull_Util_Singleton
{
    /**
     *
     * 数据库配置
     *
     * @var string
     *
     */
    protected $name   = "";
    
    /**
     *
     * 数据库表名
     *
     * @var string
     *
     */
    protected $table = "";
    
    /**
     *
     * 数据库字段详情
     *
     * @var array
     *
     */
    protected $cols   = array();

    /**
     *
     * 数据库对象
     *
     * @var Bull_Db_Front
     *
     */
    protected $db = null;

    /**
     *
     * 受保护构造器，用于实现单例模式
     *
     */
    protected function __construct($param = array())
    {
        $this->preConstruct();
        
        if (! Bull_Di_Container::has('db')) {
            $db = Bull_Di_Container::newInstance('Bull_Db_Front', $param);
            Bull_Di_Container::set('db', $db);
        }
        $this->db = Bull_Di_Container::get('db');
        $config   = Bull_Di_Container::get('config');
        $this->db->setServer(array($this->name => $config->get($this->name)));
        
        $this->postConstruct();
    }

    protected function preConstruct() {}

    protected function postConstruct() {}
    
    /**
     *
     * 获取数据表字段
     *
     * @param mixed(string | null) $strCol
     *
     * @return array
     *
     */
    public function columns($col = null)
    {
        if ($col !== null && isset($this->cols[$col]))
        {
            return $this->cols[$col];
        } else {
            return $this->cols;
        }
    }

    /**
     *
     * 获取主键字段名
     *
     * @return string
     *
     */
    public function primary()
    {
        foreach($this->cols as $col)
        {
            if ($col->primary)
            {
                return $col->name;
            }
        }
    }

    /**
     *
     * 数据库查询SQL生成器
     *
     * {{code:php
     *     $objModel->select(
     *         array("`id`", "`name`", "`age`", "`sex`"),
     *         array("`age` > ?" => 25, "`sex` = 'female'",),
     *         array("distinCT" => false, "PaginatioN" => array(20, 3), "order" => array("id DESC", "age"),
     *               "group" => array("sex", "age",), "having" => array("COUNT(age) > ?" => 60),
     *               "orwhere" => array("id < ?"=> 10),));
     * }}
     *
     * @param array $arrCols 数据表字段
     *
     * @param array $arrWhere 查询条件（AND）
     * {{code: php
     *     $arrWhere("id = $id", "age > ?" => $age);
     * }}
     *
     * @param array $arrExt 扩展条件
     * {{code: php
     *       array("distinct"   => true,    /\* DISTINCT *\/
     *             "order"      => array(), /\* ORDER BY *\/ 
     *             "pagination" => array(), /\* 每页记录数，当前第几页 *\/ 
     *             "group"      => array(), /\* GROUP BY *\/ 
     *             "orwhere"    => array(), /\* OR :WHERE *\/ 
     *             "having"     => array(), /\* HAVING *\/ 
     *             "orhaving"   => array(),); 
     * }}
     * 
     * @return obj Bull_Sql_Select
     *
     */
    public function select($cols = array('*'), $where = array(), $ext = array())
    {
        $sql = $this->db->getConnect($this->name);
        
        $select = $sql->newSelect();

        /* 选取表和字段 */
        $select->from($this->table)->cols($cols);

        /* where子句 */
        if (!empty($where))
        {
            foreach($where as $k => $v) {
                if (is_int($k))
                {
                    $select->where($v);
                } else {
                    $select->where($k, $v);
                }
            }
        }

        /* 其他 */
        if(!empty($ext))
        {
            $default_ext = array("distinct"   => true,    /* DISTINCT */
                                 "order"      => array(), /* ORDER BY */
                                 "pagination" => array(), /* 每页记录数，当前第几页 */
                                 "group"      => array(), /* GROUP BY */
                                 "orwhere"    => array(), /* OR :WHERE */
                                 "having"     => array(), /* HAVING */
                                 "orhaving"   => array(),);

            /* 转换键为小写形式 */
            $ext = array_change_key_case($ext);
            
            $act_ext = array_intersect_key($ext, $default_ext);

            /* 根据不同情况设置SQL */
            foreach($act_ext as $token => $val)
            {
                switch($token) {
                    case "distince":
                        $select->distinct($val);
                        break;
                    case "order":
                        $select->orderBy($val);
                        break;
                    case "group":
                        $select->groupBy($val);
                        break;
                    case "orwhere":
                        foreach($val as $k => $v) {
                            if (is_int($k))
                            {
                                $select->orWhere($v);
                            } else {
                                $select->orWhere($k, $v);
                            }
                        }
                        break;
                    case "having":
                        foreach($val as $k => $v) {
                            if (is_int($k))
                            {
                                $select->having($v);
                            } else {
                                $select->having($k, $v);
                            }
                        }
                        break;
                    case "orhaving":
                        foreach($val as $k => $v) {
                            if (is_int($k))
                            {
                                $select->orHaving($v);
                            } else {
                                $select->orHaving($k, $v);
                            }
                        }
                        break;
                    case "pagination":
                        $paging = intval($val[0]);         /* 每页记录数 */
                        $page   = max(1, intval($val[1])); /* 当前第几页 */
                        $select->setPaging($paging)->page($page);
                        break;
                }
            }
        }        
       return $select;
    }

    /**
     *
     * 查询所有结果
     *
     * @param $cols See select()
     *
     * @param $where See select()
     *
     * @parma $ext See select()
     *
     * @return array
     *
     */
    public function selectAll($cols = array('*'), $where = array(), $ext = array())
    {
        $sql = $this->db->getConnect($this->name);
        return $sql->fetchAll($this->select($cols, $where, $ext));
    }

    /**
     *
     * 查询单条记录
     *
     * @param $cols See select()
     *
     * @param $where See select()
     *
     * @parma $ext See select()
     *
     * @return array
     *
     */
    public function selectOne($cols = array('*'), $where = array(), $ext = array())
    {
        $sql = $this->db->getConnect($this->name);
        return $sql->fetchOne($this->select($cols, $where, $ext));
    }

    /**
     *
     * 把查询结果转换成关联数组，数组的键将是第一列的值
     * +-------------+-------+--------+---------------------+---------------------+
     * | rev_file_id | revid | fileid | addtime             | modtime             |
     * +-------------+-------+--------+---------------------+---------------------+
     * |           1 |     3 |      5 | 2012-04-24 00:00:00 | 2012-04-24 20:38:13 | 
     * |           2 |     4 |      6 | 2012-04-24 16:00:00 | 2012-04-24 20:38:13 | 
     * +-------------+-------+--------+---------------------+---------------------+
     * 如果我们查出以上结果，那么将返回一个数组(数组的键为`rev_file_id`的值)，即：
     * {{code:php
     *     array(1 => array('rev_file_id' => 1,
     *                      'revid' => 3,
     *                      // more ...
     *                ),
     *           2 => array('rev_file_id' => 2,
     *                      'revid' => 4,
     *                      // more ...
     *                ),)
     * }}
     * 
     * @see select()
     *
     * @return array
     *
     */    
    public function selectAssoc($cols = array('*'), $where = array(), $ext = array())
    {
        $sql = $this->db->getConnect($this->name);
        return $sql->fetchAssoc($this->select($cols, $where, $ext));
        
    }

    /**
     *
     * 获取查询结果中所有行第一列的值
     * +-------------+-------+--------+---------------------+---------------------+
     * | rev_file_id | revid | fileid | addtime             | modtime             |
     * +-------------+-------+--------+---------------------+---------------------+
     * |           1 |     3 |      5 | 2012-04-24 00:00:00 | 2012-04-24 20:38:13 | 
     * |           2 |     4 |      6 | 2012-04-24 16:00:00 | 2012-04-24 20:38:13 | 
     * +-------------+-------+--------+---------------------+---------------------+
     * 如果我们查出以上结果，那么将返回所有行`rev_file_id`的值，即array(1, 2)
     * 
     * @see select()
     *
     * @return array
     *
     */    
    public function selectCol($cols = array('*'), $where = array(), $ext = array())
    {
        $sql = $this->db->getConnect($this->name);
        return $sql->fetchCol($this->select($cols, $where, $ext));
    }

    /**
     *
     * 获取查询结果中第一行第一列的值
     * +-------------+-------+--------+---------------------+---------------------+
     * | rev_file_id | revid | fileid | addtime             | modtime             |
     * +-------------+-------+--------+---------------------+---------------------+
     * |           1 |     3 |      5 | 2012-04-24 00:00:00 | 2012-04-24 20:38:13 | 
     * |           2 |     4 |      6 | 2012-04-24 16:00:00 | 2012-04-24 20:38:13 | 
     * +-------------+-------+--------+---------------------+---------------------+
     * 如果我们查出以上结果，那么将返回第一行`rev_file_id`的值，即1
     * 
     * @see select()
     *
     * @return mixed
     *
     */
    public function selectValue($cols = array('*'), $where = array(), $ext = array())
    {
        $sql = $this->db->getConnect($this->name);
        return $sql->fetchValue($this->select($cols, $where, $ext));
    }

    /**
     *
     * 根据某字段查询数据库，只接收两个参数（多的参数将被忽略）：
     * 1. 当只有一个参数时，则认为根据主键查询，参数代表字段值
     * 2. 当有两个参数时，第一个参数被认为是字段名，第二个参数是字段值
     *
     * {{code:php
     *     // 1 param equals SELECT  * FROM tbl_name WHERE primary_key=1
     *     $objModel->selectBy(1);
     *     // 2 params equals SELECT * FROM tbl_name WHERE `name`='guweigang'
     *     $objModel->selectBy("name", "guweigang");
     * }}
     * 
     * @return array
     *
     */
    public function selectBy()
    {
        /* 获取参数个数 */
        $numargs = func_num_args();

        /* 当参数小于或等于0时，抛异常 */
        if ($numargs <= 0)
        {
            throw new Bull_Db_Exception(Bull_Locale::get("ERR_NO_ARGS"));
        }

        if ($numargs === 1)
        {
            /* 只有一个参数时 */
            $col = $this->primary();
            $val = func_get_arg(0);
            
        } else if($numargs >= 2) {
            /* 两个（含）以上参数时 */
            $col = func_get_arg(0);
            $val = func_get_arg(1);
        }
        
        $sql = $this->db->getConnect($this->name);

        $cond = "{$col} = ?";
        if (is_array($val))
        {
            $cond = "{$col} IN (?)";
        }
        
        return $sql->fetchAll($this->select(array("*"), array("{$cond}" => $val)));
    }

    /**
     *
     * 数据库插入（单条）操作
     *
     * {{code:php 
     *     $objModel->insert(
     *         array("revid"   => 333, "fileid" => 9,
     *               "addtime" => '2012-04-22 12:00:00',
     *               "modtime" =>'00-00-00 00:00:00'));
     * }}
     *
     * @return int 插入影响的行数
     *
     */
    public function insert($data)
    {
        /* 强制切到主库操作 */
        $sql = $this->db->connect("master", $this->name);
        
        return $sql->insert($this->table, $data);
       
    }

    /**
     *
     * 更新操作
     *
     * {{code:php
     *     $objModel->update(array('revid' => 11, 'fileid' => 12),
     *                       "`rev_file_id` = :rfid",
     *                       array('rfid' => 23));
     * }}
     *
     * @param $cols array 需要更新的字段名(数组键)及其值（数组值）
     *
     * @param $cond string 更新条件
     *
     * @param $ext array 绑定数据
     *
     * @return int 受影响的行数
     *
     */
    public function update($cols, $cond, $ext)
    {
        /* 强制切到主库操作 */
        $sql = $this->db->connect("master", $this->name);
        return $sql->update($this->table, $cols, $cond, $ext);
    }

    /**
     *
     * 删除操作
     *
     * {{code:php
     *     $objModel->delete("`rev_file_id` = :rfid", array("rfid" => 22));
     *     $objModel->delete("`rev_file_id` IN (:rfid)", array("rfid" => array(20,21)));
     * }}
     *
     * @param $cond 删除条件
     *
     * @param $ext 绑定数据
     *
     *
     */
    public function delete($cond, $ext)
    {
        /* 强制切到主库操作 */
        $sql = $this->db->connect("master", $this->name);
        return $sql->delete($this->table, $cond, $ext);
    }

    /**
     *
     * 批量插入
     *
     * {{code:php
     *    $cols = array('revid', 'fileid', 'addtime', 'modtime');
     *
     *    $datas  = array (
     *      array (
     *          'revid' => '7',
     *          'fileid' => '9',
     *          'addtime' => '2012-04-25 12:12:00',
     *          'modtime' => "2012-04-25 20:38:00", ),
     *      array (
     *          '8',
     *          '10',
     *          '2012-04-25 09:00:00',
     *          '2012-04-25 13:14:13', ));
     *
     *   $objModel->batInsert($cols, $datas);
     * }}
     *
     * @param $cols array 要插入的字段名
     *
     * @parma $datas array 要插入的数据，$datas[0]数组的键名可以为数字，只关心值
     *
     * @return int 插入影响的行数
     *
     */
    public function batInsert($cols = array(), $datas = array())
    {
        /* 强制切到主库操作 */
        $sql = $this->db->connect("master", $this->name);
        $text = 'INSERT INTO ' . $sql->quoteName($this->table);
        
        // 转译字段名
        $names = array();
        foreach ($cols as $col) {
            $names[] = $sql->quoteName($col);
        }
        
        // 拼接插入语句的字段部分
        $text .= ' (' . implode(', ', $names) . ') ';
        
        $text .= 'VALUES ';

        /* 拼接要插入的值 */
        $values = array();
        foreach($datas as $data) {
            $values[] = '('. $sql->quote($data). ')';
        }
        
        $text .= implode(',', $values);

        // return $text;
        $stmt = $sql->query($text);
        return $stmt->rowCount();
    }

    /**
     *
     * 自动验证数据，当传入数据时则直接验证，
     * 当不传入数据说则返回元素对象,留给调用者自己传值并验证
     * 
     * @param $data mixed (null | array) 待验证的数据
     *
     * @return mixed (object Bull_Form_Element | bool)
     *
     */
    public function validate($data = null)
    {
        /* 初始化过滤器 */
        $filter = new Bull_Model_Filter(new Bull_Form_Element());
        /* 初始化元素及规则 */
        $element = $filter->newFilter($this->cols);
        
        if ($data === null)
        {
            /* 如果未传值 */
            return $element;
            
        } elseif (is_array($data)) {
            /* 装载数据 */
            $element->setValues($data);
            /* 验证 */
            $element->validate();

            return $element;
            
        } else {
            /* 异常 */
            throw new Bull_Model_Exception(Bull_Locale::get("ERR_ILLEGAL_PARAMS"));
        }
    }
}