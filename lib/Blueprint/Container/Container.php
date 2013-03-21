<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Container;

/**
 * Container class.
 *
 * Provides a set of methods for constructing a container to hold 
 * dependencies for another class and inject them into it
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Container {

    /**
     * values
     *
     * Holds the dependencies.
     * 
     * @var mixed
     * @access protected
     */
    protected $values;

    /**
     * __construct function.
     *
     * Loads initial dependencies.
     * 
     * @access public
     * @param array $values (default: array())
     * @return void
     */
    public function __construct($values = array()) {

        $this->values = $values;

    }

    /**
     * set function.
     *
     * Adds further dependencies one at a time.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value) {

        $this->values[$key] = $value;

    }

    /**
     * get function.
     *
     * Used to inject dependency into the calling class.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function get($key) {
        
        if (array_key_exists($key, $this->values))
            return $this->values[$key];
    
    }

    /**
     * exists function.
     *
     * Checks to see if the dependency exists.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function exists($key) {
    
        return isset($this->values[$key]);
        
    }
    
    /**
     * unsetKey function.
     *
     * Removes the dependency from the container.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function unsetKey($key) {

        unset($this->values[key]);

    }

}