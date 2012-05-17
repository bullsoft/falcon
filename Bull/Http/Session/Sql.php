<?php
/**
 * 
 * Session hander for SQL based data store.
 * 
 * @package Bull.Http.Session
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Http_Session_Sql extends Bull_Http_Session_Abstract
{
   
    /**
     * 
     * Bull_Sql_Adapter_Abstract object to connect to the database.
     * 
     * @var Bull_Sql_Adapter_Abstract
     * 
     */
    protected $_sql;

    /**
     *
     * @params string table Table where the session data will be stored, default
     * 'sessions'.
     * 
     * @params string created_col Column name where time of creation is to be 
     * stored, default 'created'.
     * 
     * @params string updated_col Column name where time of update is to be 
     * stored, default 'updated'.
     * 
     * @params string id_col Column name of the session id, default 'id'.
     * 
     * @params string data_col Column name where the actual session data will 
     * be stored, default 'data'.
     *
     */
    protected $_params = array(
        'table'       => 'sessions',
        'id_col'      => 'id',
        'created_col' => 'created',
        'updated_col' => 'updated',
        'data_col'    => 'data',
    );
    /**
     * 
     * Constructor.
     * 
     */
    public function __construct(Bull_Sql_Adapter_Abstract $sql, $params)
    {
        parent::__construct();
        $this->_sql = $sql;
        $this->_params = array_merge($this->_params, $params);
    }
    
    /**
     * 
     * Open session handler.
     * 
     * @return bool
     * 
     */
    public function open()
    {
        return true;
    }
    
    /**
     * 
     * Close session handler.
     * 
     * @return bool
     * 
     */
    public function close()
    {
        $this->_sql = null;
        return true;
    }
    
    /**
     * 
     * Reads session data.
     * 
     * @param string $id The session ID.
     * 
     * @return string The serialized session data.
     * 
     */
    public function read($id)
    {
        $select = $this->_sql->newSelect();
        $select->from($this->_params['table'])
               ->cols(array($this->_params['data_col']))
               ->where(array("{$this->_params['id_col']} = ?" =>  $id));

        return $this->_sql->fetchValue($select);
    }
    
    /**
     * 
     * Writes session data.
     * 
     * @param string $id The session ID.
     * 
     * @param string $data The serialized session data.
     * 
     * @return bool
     * 
     */
    public function write($id, $data)
    {
        $select = $this->_sql->newSelect();
        
        // select up to 2 records from the database
        $select->from($this->_params['table'])
               ->cols(array($this->_params['data_col']))
               ->where(array("{$this->_params['id_col']} = ?" =>  $id))
               ->limit(2);
            
        // use fetchCol() instead of countPages() for speed reasons.
        // count on some DBs is pretty slow, so this will fetch only
        // the rows we need.
        $rows  = $this->_sql->fetchCol($select);
        $count = count((array) $rows);
        
        // insert or update?
        if ($count == 0) {
            // no data yet, insert
            return $this->_insert($id, $data);
        } elseif ($count == 1) {
            // existing data, update
            return $this->_update($id, $data);
        } else {
            // more than one row means an ID collision
            // @todo log this somehow?
            return false;
        }
    }
    
    /**
     * 
     * Destroys session data.
     * 
     * @param string $id The session ID.
     * 
     * @return bool
     * 
     */
    public function destroy($id)
    {
        $this->_sql->delete(
            $this->_params['table'],
            array("{$this->_params['id_col']} = ?" => $id)
        );
        
        return true;
    }
    
    /**
     * 
     * Removes old session data (garbage collection).
     * 
     * @param int $lifetime Removes session data not updated since this many
     * seconds ago.  E.g., a lifetime of 86400 removes all session data not
     * updated in the past 24 hours.
     * 
     * @return bool
     * 
     */
    public function gc($lifetime)
    {
        // timestamp is current time minus session.gc_maxlifetime
        $timestamp = date(
            'Y-m-d H:i:s',
            mktime(date('H'), date('i'), date('s') - $lifetime)
        );
        
        // delete all sessions last updated before the timestamp
        $this->_sql->delete($this->_params['table'], array(
            "{$this->_params['updated_col']} < ?" => $timestamp,
        ));
        
        return true;
    }
    
    /**
     * 
     * Inserts a new session-data row in the database.
     * 
     * @param string $id The session ID.
     * 
     * @param string $data The serialized session data.
     * 
     * @return bool
     * 
     */
    protected function _insert($id, $data)
    {
        $now = date('Y-m-d H:i:s');
        
        $cols = array(
            $this->_params['created_col'] => $now,
            $this->_params['updated_col'] => $now,
            $this->_params['id_col']      => $id,
            $this->_params['data_col']    => $data,
        );
        
        try {
            $this->_sql->insert($this->_params['table'], $cols);
            return true;
        } catch (Bull_Sql_Exception $e) {
            // @TODO log this somehow?
            return false;
        }
    }
    
    /**
     * 
     * Updates an existing session-data row in the database.
     * 
     * @param string $id The session ID.
     * 
     * @param string $data The serialized session data.
     * 
     * @return bool
     * 
     * @todo Should we log caught exceptions?
     *
     */
    protected function _update($id, $data)
    {
        $cols = array(
            $this->_params['updated_col'] => date('Y-m-d H:i:s'),
            $this->_params['data_col']    => $data,
        );
        
        $where = array("{$this->_params['id_col']} = ?" => $id);
        
        try {
            $this->_sql->update($this->_params['table'], $cols, $where);
            return true;
        } catch (Bull_Sql_Exception $e) {
            // @TODO log this somehow?
            return false;
        }
    }
}