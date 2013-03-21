<?php

/*
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Chat\Database;

/**
 * MongoDatabase class.
 *
 * Provides a set of methods for interacting with a database through the 
 * PHP MongoDB Object
 *
 * @package chat
 * @author Christopher <chris@jooldesign.co.uk>
 */
class MongoDatabase {

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
                $this->config->database['name'], 
                $this->config->database['host'], 
                $this->config->database['port']
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
     * Maps MongoDB methods to the Mongo object.
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
     * Maps MongoDB properties to the Mongo object.
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
     * Sets properties in the MongoDB object.
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
     * Instantiates the MongoDB object and create the database handle.
     * 
     * @access private
     * @param mixed $host
     * @param mixed $name
     * @param mixed $user
     * @param mixed $pass
     * @return void
     */
    private function openConnection($name, $host=false, $port=false) {
    
        $dsn = empty($host) ? '' : (empty($port) ? 'mongodb://' . $host : 'mongodb://' . $host. ':' . $port);
    
        try {
        
            if (!is_object($this->dbh)) {

                $mongo = new \MongoClient($dsn);
                $this->dbh = $mongo->$name;

            }
                
        } catch (PDOException $e) {
                
            throw new \Exception('MongoDB connection failed');
            
        }
    
    }
    
    /**
     * closeConnection function.
     *
     * Destroys the MongoDB object thus closing the database connection.
     * 
     * @access private
     * @return void
     */
    private function closeConnection() {
    
        if (is_object($this->dbh))
            $this->dbh = NULL;
    
    }

}

?>