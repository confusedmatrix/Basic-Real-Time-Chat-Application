<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Session;

/**
 * Session class.
 *
 * Manages a session via a databse
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Session {

    /**
     * config
     * 
     * @var mixed
     * @access protected
     */
    protected $config;

    /**
     * database
     * 
     * @var mixed
     * @access protected
     */
    protected $database;
    
    /**
     * __construct function.
     *
     * Loads dependencies.
     * 
     * @access public
     * @param mixed $database
     * @param mixed $database
     * @return void
     */
    public function __construct($config, $database) {
    
        $this->config = $config;
        $this->database = $database;
        $this->startSession();
    
    }

    /**
     * startSession function.
     *
     * Registers session save handlers, sets some session settings and 
     * starts a session.
     * 
     * @access private
     * @return void
     */
    private function startSession() {
        
        session_set_save_handler(
            
            array($this, '_open'), 
            array($this, '_close'),    
            array($this, '_read'), 
            array($this, '_write'), 
            array($this, '_destroy'), 
            array($this, '_clean')
            
        );
        
        register_shutdown_function('session_write_close');
        
        ini_set('session.gc_maxlifetime', $this->config->defaults['session']['expiry']);
        ini_set('session.gc_probability', $this->config->defaults['session']['probability']);
        ini_set('session.gc_divisor', $this->config->defaults['session']['divisor']);
        session_start();
            
    }
    
    /**
     * endSession function.
     *
     * Unsets the user's session.
     * 
     * @access public
     * @return void
     */
    public function endSession() {
        
        unset($_SESSION);
        session_destroy();
        
    }
    
    /**
     * set function.
     *
     * Sets a value into the user's session.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value) {

        $_SESSION[$key] = $value;

    }

    /**
     * get function.
     *
     * Gets a value from the user's session.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function get($key) {
        
        if (array_key_exists($key, $_SESSION))
            return $_SESSION[$key];
    
    }

    /**
     * exists function.
     *
     * Checks if a value exists in the user's session.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function exists($key) {
    
        return isset($_SESSION[$key]);
        
    }
    
    /**
     * unsetKey function.
     *
     * Removes a value from the user's session.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function unsetKey($key) {

        unset($_SESSION[$key]);

    }
    
    /**
     * _open function.
     *
     * Open handler for the session.
     * 
     * @access public
     * @return void
     */
    public function _open() {
        
        return true;
        
    }
     
    /**
     * _close function.
     *
     * Close handler for the session.
     * 
     * @access public
     * @return void
     */
    public function _close() {
    
        return true;
    
    }
     
    /**
     * _read function.
     *
     * Read handler for the session.
     * 
     * @access public
     * @param mixed $id
     * @return void
     */
    public function _read($id) {
        
        $q = $this->database->prepare("SELECT session_data FROM sessions WHERE session_id = ?");
        $q->execute(array($id));
     
        $result = $q->fetch(\PDO::FETCH_OBJ);

        if (isset($result->session_data))
            return $result->session_data;
            
        return '';
            
    }
     
    /**
     * _write function.
     *
     * Write handler for the session.
     * 
     * @access public
     * @param mixed $id
     * @param mixed $data
     * @return void
     */
    public function _write($id, $data)    {
        
        $timestamp = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $q = $this->database->prepare("REPLACE INTO sessions VALUES (?, ?, ?, ?)");
        
        return $q->execute(array($id, $data, $ip, $timestamp));
        
    }
     
    /**
     * _destroy function.
     *
     * Destroy handler for the session.
     * 
     * @access public
     * @param mixed $id
     * @return void
     */
    public function _destroy($id) {
        
        $q = $this->database->prepare("DELETE FROM sessions WHERE session_id = ?");
        
        return $q->execute(array($id));
        
    }
     
    /**
     * _clean function.
     *
     * Clean handler for the session.
     * 
     * @access public
     * @param mixed $max
     * @return void
     */
    public function _clean($max) {
        
        $old = time() - $max;
     
        $q = $this->database->prepare("DELETE FROM sessions WHERE session_timestamp < ?");
        
        return $q->execute(array($old));
        
    }

}