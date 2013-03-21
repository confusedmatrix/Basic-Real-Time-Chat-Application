<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Authentication;

/**
 * Authentication class.
 *
 * Provides a simple set of methods to authenticate a user against a user
 * a user table in the database.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Authentication {
    
    /**
     * database
     * 
     * @var mixed
     * @access protected
     */
    protected $database;
    
    
    /**
     * session
     * 
     * @var mixed
     * @access protected
     */
    protected $session;
    
    
    /**
     * __construct function.
     *
     * Loads dependencies.
     * 
     * @access public
     * @param mixed $database
     * @param mixed $session
     * @return void
     */
    public function __construct($database, $session) {
    
        $this->database = $database;
        $this->session = $session;
    
    }
    
    
    /**
     * login function.
     *
     * Attempts to authenticate user against the user table in the 
     * database.
     * 
     * @access public
     * @param mixed $username
     * @param mixed $password
     * @return void
     */
    public function login($username, $password) {
    
        $options = array(
        
            'table'     => 'users',
            'select'    => array(
                'user_id',
                'user_name', 
                'password'
            ),
            'where'    => array(
                array(
                    'user_name',
                    '=',
                    $username
                ),
                array(
                    'password',
                    '=',
                    sha1($password)
                )
            )
        
        );
    
        $row = $this->database->fetchRow($options);
        
        if (empty($row))
            return false;
            
        $this->session->set('logged_in', true);
        $this->session->set('user_id', $row['user_id']);

        session_regenerate_id();
        
        return true;
    
    }
    
    /**
     * logout function.
     *
     * Removes state from the user's session, thus de-authenticating
     * the user.
     * 
     * @access public
     * @return void
     */
    public function logout() {
    
        $this->session->endSession();
    
    }

}