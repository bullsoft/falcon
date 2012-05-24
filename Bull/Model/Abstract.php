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
    protected $name = "";
    
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
    protected $cols = array();

    /**
     *
     * 数据库前端对象
     *
     * @var Bull_Sql_Front
     *
     */
    protected $sql_front = null;

    /**
     *
     * 过滤器
     *
     */
    protected $filter = null;
    /**
     * 
     * The column name for 'created' timestamps; default is 'created'.
     * 
     * @var string
     * 
     */
    protected $created_col = 'created';
    
    /**
     * 
     * The column name for 'updated' timestamps; default is 'updated'.
     * 
     * @var string
     * 
     */
    protected $updated_col = 'updated';
    
    /**
     * 
     * Other models that relate to this model should use this as the 
     * foreign-key column name.
     * 
     * @var string
     * 
     */
    protected $foreign_col = null;
    
    /**
     *
     * 关系模型
     *
     * @var array
     *
     */
    protected $related    = array();
    protected $cache      = null;
    
    /**
     *
     * 受保护构造器，用于实现单例模式
     *
     */
    protected function __construct($params = array())
    {
        $this->preConstruct();
        
        if (! Bull_Di_Container::has('sql_front')) {
            $sql_front = Bull_Di_Container::newInstance('Bull_Sql_Front', $params);
            Bull_Di_Container::set('sql_front', $sql_front);
        }
        $this->sql_front = Bull_Di_Container::get('sql_front');
        $config = Bull_Di_Container::get('config');
        $this->sql_front->setServer(array($this->name => $config->get($this->name)));
        
        $this->postConstruct();
        
        $filter = new Bull_Model_Filter(new Bull_Form_Element());
        $this->filter = $filter->newFilter($this->cols);

        $this->buildRelated();
    }

    protected function preConstruct() {}

    protected function postConstruct() {}

    protected function buildRelated() {}

    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

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
        if ($col !== null && isset($this->cols[$col])) {
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
        foreach($this->cols as $col) {
            if ($col->primary) {
                return $col->name;
            }
        }
    }

    /**
     * 
     * Adds a named has-one relationship.
     * 
     * @param string $name The relationship name, which will double as a
     * property when records are fetched from the model.
     * 
     * @param array $opts Additional options for the relationship.
     * 
     * @return void
     * 
     */
    protected function hasOne($name, $opts = null)
    {
        settype($opts, 'array');
        if (empty($opts['class'])) {
            $opts['class'] = 'Bull_Model_Related_HasOne';
        }
        $this->addRelated($name, $opts);
    }
    
    /**
     * 
     * Adds a named has-one-or-none relationship.
     * 
     * @param string $name The relationship name, which will double as a
     * property when records are fetched from the model.
     * 
     * @param array $opts Additional options for the relationship.
     * 
     * @return void
     * 
     */
    protected function hasOneOrNull($name, $opts = null)
    {
        settype($opts, 'array');
        if (empty($opts['class'])) {
            $opts['class'] = 'Bull_Model_Related_HasOneOrNull';
        }
        $this->addRelated($name, $opts);
    }
    
    /**
     * 
     * Adds a named belongs-to relationship.
     * 
     * @param string $name The relationship name, which will double as a
     * property when records are fetched from the model.
     * 
     * @param array $opts Additional options for the relationship.
     * 
     * @return void
     * 
     */
    protected function belongsTo($name, $opts = null)
    {
        settype($opts, 'array');
        if (empty($opts['class'])) {
            $opts['class'] = 'Bull_Model_Related_BelongsTo';
        }
        $this->addRelated($name, $opts);
    }
    
    /**
     * 
     * Adds a named has-many relationship.
     * 
     * Note that you can get "has-and-belongs-to-many" using "has-many"
     * with a "through" option ("has-many-through").
     * 
     * @param string $name The relationship name, which will double as a
     * property when records are fetched from the model.
     * 
     * @param array $opts Additional options for the relationship.
     * 
     * @return void
     * 
     */
    protected function hasMany($name, $opts = null)
    {
        settype($opts, 'array');
        // maintain backwards-compat for has-many with 'through' option
        if (! empty($opts['through'])) {
            return $this->hasManyThrough($name, $opts['through'], $opts);
        }
        if (empty($opts['class'])) {
            $opts['class'] = 'Bull_Model_Related_HasMany';
        }
        $this->addRelated($name, $opts);
    }
    
    /**
     * 
     * Adds a named has-many through relationship.
     * 
     * Note that you can get "has-and-belongs-to-many" using "has-many"
     * with a "through" option ("has-many-through").
     * 
     * @param string $name The relationship name, which will double as a
     * property when records are fetched from the model.
     * 
     * @param string $through The relationship name that acts as the "through"
     * model (i.e., the mapping model).
     * 
     * @param array $opts Additional options for the relationship.
     * 
     * @return void
     * 
     */
    protected function hasManyThrough($name, $through, $opts = null)
    {
        settype($opts, 'array');
        if (empty($opts['class'])) {
            $opts['class'] = 'Bull_Model_Related_HasManyThrough';
        }
        $opts['through'] = $through;
        $this->addRelated($name, $opts);
    }
    
    /**
     * 
     * Support method for adding relations.
     * 
     * @param string $name The relationship name, which will double as a
     * property when records are fetched from the model.
     * 
     * @param array $opts Additional options for the relationship.
     * 
     * @return void
     * 
     */
    protected function addRelated($name, $opts)
    {
        // is the related name already a column name?
        if (array_key_exists($name, $this->cols)) {
            throw new Bull_Model_Exception('ERR_RELATED_CONFLICT');
        }
        // is the related name already in use?
        if (array_key_exists($name, $this->related)) {
            throw new Bull_Model_Exception('ERR_RELATED_EXISTS');
        }
        // keep it!
        $opts['name'] = $name;
        $this->related[$name] = $opts;
    }
    
    /**
     * 
     * Gets the control object for a named relationship.
     * 
     * @param string $name The related name.
     * 
     * @return Bull_Model_Related The relationship control object.
     * 
     */
    public function getRelated($name)
    {
        if (! array_key_exists($name, $this->related)) {
            throw new Bull_Model_Exception('ERR_NO_SUCH_RELATED');
        }
        if (is_array($this->related[$name])) {
            $opts = $this->related[$name];
            $this->related[$name] = Bull_Di_Container::newInstance($opts['class']);
            unset($opts['class']);
            $this->related[$name]->setNativeModel($this);
            $this->related[$name]->load($opts);
        }
        return $this->related[$name];
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
        $sql = $this->sql_front->getConnect($this->name);
        $select = $sql->newSelect();

        /* 选取表和字段 */
        $select->from($this->table)->cols($cols);
        /* where子句 */
        if (!empty($where)) {
            foreach($where as $k => $v) {
                if (is_int($k)) {
                    $select->where($v);
                } else {
                    $select->where($k, $v);
                }
            }
        }
        /* 其他 */
        if(!empty($ext)) {
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
            foreach($act_ext as $token => $val) {
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
                            if (is_int($k)) {
                                $select->orWhere($v);
                            } else {
                                $select->orWhere($k, $v);
                            }
                        }
                        break;
                    case "having":
                        foreach($val as $k => $v) {
                            if (is_int($k)) {
                                $select->having($v);
                            } else {
                                $select->having($k, $v);
                            }
                        }
                        break;
                    case "orhaving":
                        foreach($val as $k => $v) {
                            if (is_int($k)) {
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
        $sql = $this->sql_front->getConnect($this->name);
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
        $sql = $this->sql_front->getConnect($this->name);
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
        $sql = $this->sql_front->getConnect($this->name);
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
        $sql = $this->sql_front->getConnect($this->name);
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
        $sql = $this->sql_front->getConnect($this->name);
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
        if ($numargs <= 0) {
            throw new Bull_Db_Exception(Bull_Locale::get("ERR_NO_ARGS"));
        }

        if ($numargs === 1) {
            /* 只有一个参数时 */
            $col = $this->primary();
            $val = func_get_arg(0);
        } else if($numargs >= 2) {
            /* 两个（含）以上参数时 */
            $col = func_get_arg(0);
            $val = func_get_arg(1);
        }
        $sql = $this->sql_front->getConnect($this->name);
        $cond = "{$col} = ?";
        if (is_array($val)) {
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
        $sql = $this->sql_front->connect("master", $this->name);
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
    public function update($cols, $cond, array $ext)
    {
        /* 强制切到主库操作 */
        $sql = $this->sql_front->connect("master", $this->name);
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
        $sql = $this->sql_front->connect("master", $this->name);
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
        $sql = $this->sql_front->connect("master", $this->name);
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
     * Get last instert id.
     *
     */
    public function lastInsertId()
    {
        $sql = $this->sql_front->getConnect($this->name);
        return $sql->lastInsertId();
    }

    /**
     *
     * Get Sql Object
     *
     */
    public function getSql()
    {
        return $this->sql_front->getConnect($this->name);
    }

    public function getRecord(array $data = array())
    {
        $record = new Bull_Model_Record();
        $insert = array();
        if (!empty($data)) {
            foreach($this->cols as $key => $col) {
                if (isset($data[$key])) {
                    $insert[$key] = $data[$key];
                } else {
                    $insert[$key] = $col->default;
                }
            }
            foreach($this->related as $key => $value) {
                if (isset($data[$key])) {
                    $insert[$key] = $data[$key];
                }
            }
        }
        $record->init($this, $insert);
        return $record;
    }

    public function newRecord(array $data = array())
    {
        $record = $this->getRecord($data);
        $record->initNew();
        return $record;
    }

    public function getCollection(array $data = array())
    {
        $collection = new Bull_Model_Collection();
        $collection->setModel($this);
        $collection->load($data);
        return $collection;
    }

    public function newCollection(array $data = array())
    {
        $collection = $this->getCollection($data);
        return $collection;
    }
}
