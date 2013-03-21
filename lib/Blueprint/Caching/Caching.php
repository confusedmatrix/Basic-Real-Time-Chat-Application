<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Caching;

/**
 * Caching class.
 *
 * Base caching class from which all other caching classes inherit
 * 
 * @package blueprint
 * @abstract
 * @author Christopher <chris@jooldesign.co.uk>
 */
abstract class Caching {

    /**
     * config
     * 
     * @var mixed
     * @access protected
     */
    protected $config;

    /**
     * status
     * 
     * (default value: true)
     * 
     * @var bool
     * @access public
     */
    public $status = true;
    
    /**
     * expiry
     * 
     * (default value: 604800)
     * 
     * @var int
     * @access public
     */
    public $expiry = 604800; // 7 days
    
    /**
     * cache_dir
     * 
     * @var mixed
     * @access public
     */
    public $cache_dir;
    
    /**
     * __construct function.
     *
     * Loads dependencies and sets cache directory, status and expiry time.
     * 
     * @access public
     * @param mixed $config
     * @param mixed $cache_dir
     * @return void
     */
    public function __construct($config, $cache_dir) {
        
        $this->config = $config;
        
        $this->status = $this->config->defaults['caching']['status'];
        $this->expiry = $this->config->defaults['caching']['expiry'];
        $this->cache_dir = $cache_dir;
    
    }

}