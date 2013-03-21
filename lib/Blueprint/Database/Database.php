<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Blueprint\Database;

/**
 * Database class.
 *
 * Provides a set of methods for interacting with a database through the 
 * PDO object
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Database {

    /**
     * config
     * 
     * @var mixed
     * @access protected
     */
    protected $config;

    /**
     * dbh
     * 
     * (default value: NULL)
     * 
     * @var mixed
     * @access private
     */
    private $dbh = NULL;
    
    /**
     * __construct function.
     *
     * Loads dependencies and opens connection based on config settings.
     * 
     * @access public
     * @param mixed $config
     * @return void
     */
    public function __construct($config) {
    
        $this->config = $config;
    
        if (!is_object($this->dbh)) {

            $this->openConnection(
            
                $this->config->database['host'], 
                $this->config->database['name'], 
                $this->config->database['user'], 
                $this->config->database['pass']
                
            );
        
        }
    
    }
    
    /**
     * __destruct function.
     *
     * Closes the connection when the class in destroyed.
     * 
     * @access public
     * @return void
     */
    public function __destruct() {
    
        $this->closeConnection();
    
    }
    
    /**
     * __call function.
     * 
     * Maps PDOStatement methods to the PDO object.
     *
     * @access public
     * @param mixed $method
     * @param mixed $attributes
     * @return void
     */
    public function __call($method, $attributes) {
    
        if (method_exists($this->dbh, $method)) {
            
            return call_user_func_array(array($this->dbh, $method), $attributes);
            
        } else {
            
            throw new \Exception('Method or property ' . $method . ' does not exist.');
            
        }
    
    }
    
    /**
     * __get function.
     * 
     * Maps PDO properties to the PDO object.
     *
     * @access public
     * @param mixed $key
     * @return void
     */
    public function __get($key) {
    
        if (isset($this->dbh->$key))
            return $this->dbh->$key;
            
        return false;
    
    }
    
    /**
     * __set function.
     *
     * Sets properties in the PDO object.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value) {
    
        $this->dbh->$key = $value;
    
    }

    /**
     * openConnection function.
     *
     * Instantiates the PDO object and create the database handle.
     * 
     * @access private
     * @param mixed $host
     * @param mixed $name
     * @param mixed $user
     * @param mixed $pass
     * @return void
     */
    private function openConnection($host, $name, $user, $pass) {
    
        $dsn = 'mysql:dbname='.$name.';host='.$host;
    
        try {
        
            if (!is_object($this->dbh)) {
        
                $this->dbh = new \PDO($dsn, $user, $pass, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
            }
                
        } catch (PDOException $e) {
                
            echo 'Connection failed: '.$e->getMessage();
            
        }
    
    }
    
    /**
     * closeConnection function.
     *
     * Destroys the PDO object thus closing the database connection.
     * 
     * @access private
     * @return void
     */
    private function closeConnection() {
    
        if (is_object($this->dbh))
            $this->dbh = NULL;
    
    }
    
    /**
     * fetchRow function.
     * 
     * Performs a select query and returns a single row from the database.
     *
     * @access public
     * @param array $options (default: array())
     * @return void
     */
    public function fetchRow($options=array()) {
        
        try {
            
            // unset the limit as we only want to pull 1 result
            if (!empty($options['limit']))
                unset($options['limit']);
        
            $s = $this->buildSQLSelectQuery($options);
            
            $q = $this->dbh->prepare($s['sql']);
            $q->execute($s['replacements']);
            
            $data = array();
            $data = $q->fetch(\PDO::FETCH_ASSOC);

            if (count($options['select']) == 1 && $options['select'][0] != '*')
                $data = $data[key($data)];
                        
            if (!empty($data))
                return $data;
                
            return false;
            
        } catch (Exception $e) {
            
            echo 'The following exception was given: '.$e;
            exit;
            
        }
    
    }
    
    /**
     * fetchRows function.
     *
     * Performs a select query and returns rows from the database.
     * 
     * @access public
     * @param array $options (default: array())
     * @return void
     */
    public function fetchRows($options=array()) {
        
        try {
        
            $s = $this->buildSQLSelectQuery($options);

            $q = $this->dbh->prepare($s['sql']);
            $q->execute($s['replacements']);
            
            $data = array();
            while ($result = $q->fetch(\PDO::FETCH_ASSOC)) {
                
                if (count($options['select']) == 1 && $options['select'][0] != '*') {
                    
                    $data[] = $result[key($result)];

                } else {

                    $array = array();
                    foreach ($result as $key => $val) {
                    
                        $array[$key] = $val;
                    
                    }        
                    
                    $data[] = $array;

                }           
            
            }
                        
            if (!empty($data))
                return $data;
            
            return array();
            
        } catch (Exception $e) {
            
            echo 'The following exception was given: '.$e;
            exit;
            
        }
    
    }
    
    /**
     * insert function.
     *
     * Perfoms an insert query.
     * 
     * @access public
     * @param mixed $table
     * @param mixed $values
     * @return void
     */
    public function insert($table, $values) {
    
        try {
    
            $s = $this->buildSQLInsertQuery($table, $values);
                
            $q = $this->dbh->prepare($s['sql']);
            $q->execute($s['replacements']);
                
            return true;
            
        } catch (Exception $e) {
            
            echo 'The following exception was given: '.$e;
            exit;
            
        }
        
    }
    
    /**
     * update function.
     *
     * Performs an update query.
     * 
     * @access public
     * @param mixed $table
     * @param array $values (default: array())
     * @param array $where (default: array())
     * @return void
     */
    public function update($table, $values=array(), $where=array()) {
    
        try {
    
            $s = $this->buildSQLUpdateQuery($table, $values, $where);
                
            $q = $this->dbh->prepare($s['sql']);
            $q->execute($s['replacements']);
            
            return true;
            
        } catch (Exception $e) {
            
            echo 'The following exception was given: '.$e;
            exit;
            
        }
    
    }
    
    /**
     * delete function.
     *
     * Performs a delete query.
     * 
     * @access public
     * @param mixed $table
     * @param array $where (default: array())
     * @return void
     */
    public function delete($table, $where=array()) {
        
        try {
            
            $s = $this->buildSQLDeleteQuery($table, $where);
            
            $q = $this->dbh->prepare($s['sql']);
            $q->execute($s['replacements']);
            
            return true;
            
        } catch (Exception $e) {
            
            echo 'The following exception was given: '.$e;
            exit;
            
        }
        
    }

    /**
     * buildSQLSelectQuery function.
     *
     * Builds a select SQL query
     * 
     * @access protected
     * @param array $options (default: array())
     * @return void
     */
    protected function buildSQLSelectQuery($options=array()) {
    
        $replacements = array();
    
        $sql = 'SELECT ';
        $sql .= implode(', ', $options['select']);
        
        $sql .= " \nFROM ".$options['table'];
        $sql .= !empty($options['joins']) ? " \n".$options['joins'] : '';
    
        if (!empty($options['where'])) {
            
            $sql .= " \nWHERE ";
            foreach ($options['where'] as $w) {
            
                if (!is_array($w[0])) {
                
                    $sql .= $w[0].' '.$w[1].' ?';
                    $replacements[] = $w[2];
                    
                } else {
                    
                    $sql .= '(';
                    foreach ($w as $sw) {
                    
                        $sql .= $sw[0].' '.$sw[1].' ? OR ';
                        $replacements[] = $sw[2];
                    
                    }
                    $sql = substr($sql, 0, -4);
                    $sql .= ')';
                    
                }
                    
                $sql .= " \nAND ";
                
            }
                
            $sql = substr($sql, 0, -5);
                
        }
        
        if (!empty($options['distinct'])) {
        
            $sql .= " \nGROUP BY ";
            $sql .= implode(', ', $options['distinct']);
        
        }
            
        if (!empty($options['order'])) {
            
            $sql .= " \nORDER BY ";
            foreach ($options['order'] as $key => $value) {
                
                $sql .= $key.' '.$value;
                $sql .= ', ';
                
            }
                
            $sql = substr($sql, 0, -2);
                
        }
        
        if (!empty($options['limit'])) {
        
            $sql .= " \nLIMIT ".$options['limit'][0].", ".$options['limit'][1];
        
        }
        
        return array(
        
            'sql'             => $sql,
            'replacements'    => $replacements
            
        );
        
    }
    
    /**
     * buildSQLInsertQuery function.
     *
     * Builds an insert SQL query
     * 
     * @access protected
     * @param mixed $table
     * @param array $values (default: array())
     * @return void
     */
    protected function buildSQLInsertQuery($table, $values=array()) {
    
        $replacements = array();
    
        $sql = 'INSERT INTO '.$table.' (';
        $sql .= implode(', ', array_keys($values));
        
        $sql .= ") \nVALUES (";
        foreach ($values as $val) {
            
            $sql .= '?, ';        
                
        }
        $sql = substr($sql, 0, -2);
        $sql .= ') ';
        
        return array(
        
            'sql'             => $sql,
            'replacements'    => array_values($values)
            
        );
        
    }
    
    /**
     * buildSQLUpdateQuery function.
     *
     * Builds an update SQL query
     * 
     * @access protected
     * @param mixed $table
     * @param array $values (default: array())
     * @param array $where (default: array())
     * @return void
     */
    protected function buildSQLUpdateQuery($table, $values=array(), $where=array()) {
    
        $replacements = array();
    
        $sql = 'UPDATE '.$table;        
        
        $sql .= " \nSET ";
        foreach ($values as $key => $val) {
            
            $sql .= $key.' = ?, ';
            $replacements[] = $val;        
                
        }
        $sql = substr($sql, 0, -2);
        
        if (!empty($where)) {
            
            $sql .= " \nWHERE ";
            foreach ($where as $w) {
            
                if (!is_array($w[0])) {
                
                    $sql .= $w[0].' '.$w[1].' ?';
                    $replacements[] = $w[2];
                    
                } else {
                    
                    $sql .= '(';
                    foreach ($w as $sw) {
                    
                        $sql .= $sw[0].' '.$sw[1].' ? OR ';
                        $replacements[] = $sw[2];
                    
                    }
                    $sql = substr($sql, 0, -4);
                    $sql .= ')';
                    
                }
                    
                $sql .= " \nAND ";
                
            }
                
            $sql = substr($sql, 0, -5);
                
        }
        
        return array(
        
            'sql'             => $sql,
            'replacements'    => $replacements
            
        );
        
    }
    
    /**
     * buildSQLDeleteQuery function.
     *
     * Builds a delete SQL query
     * 
     * @access protected
     * @param mixed $table
     * @param array $where (default: array())
     * @return void
     */
    protected function buildSQLDeleteQuery($table, $where=array()) {
    
        $replacements = array();
    
        $sql = 'DELETE FROM '.$table;
    
        if (!empty($where)) {
            
            $sql .= " \nWHERE ";
            foreach ($where as $w) {
            
                if (!is_array($w[0])) {
                
                    $sql .= $w[0].' '.$w[1].' ?';
                    $replacements[] = $w[2];
                    
                } else {
                    
                    $sql .= '(';
                    foreach ($w as $sw) {
                    
                        $sql .= $sw[0].' '.$sw[1].' ? OR ';
                        $replacements[] = $sw[2];
                    
                    }
                    $sql = substr($sql, 0, -4);
                    $sql .= ')';
                    
                }
                    
                $sql .= " \nAND ";
                
            }
                
            $sql = substr($sql, 0, -5);
                
        }
                
        return array(
        
            'sql'             => $sql,
            'replacements'    => $replacements
            
        );
        
    }

}

?>