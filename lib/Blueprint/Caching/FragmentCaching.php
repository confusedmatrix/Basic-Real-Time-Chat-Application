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
 * PageCaching class.
 *
 * Provides a set of methods for caching whole page outputs
 * 
 * @package blueprint
 * @extends Caching
 * @author Christopher <chris@jooldesign.co.uk>
 */
class FragmentCaching extends Caching {
    
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
    
        parent::__construct($config, $cache_dir);
        
        $this->status = $this->config->defaults['fragment_caching']['status'];
        $this->expiry = $this->config->defaults['fragment_caching']['expiry'];
    
    }
    
    /**
     * setCacheFileName function.
     *
     * Determines the name and location of the cache file.
     * 
     * @access protected
     * @param mixed $key
     * @return void
     */
    protected function setCacheFileName($key) {
        
        return $this->cache_dir . $key . ".cache";
    
    }
    
    /**
     * set function.
     *
     * Stores a value in the cache file.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $val
     * @return void
     */
    public function set($key, $val) {
    
        if ($this->status === false)
            return false;
        
        $file = $this->setCacheFileName($key);
        file_put_contents($file, json_encode($val));
        
    
    }
    
    /**
     * get function.
     *
     * Retrieves a value from the cache file.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function get($key) {
    
        if ($this->status === false)
            return false;
    
        $file = $this->setCacheFileName($key);
        if (file_exists($file) && ((time() - $this->expiry) < filemtime($file))) {
            
            $var = file_get_contents($file);
            return json_decode($var, 1);
        
        }
        
        return false;
    
    }
    
    /**
     * clear function.
     *
     * Remove a cached fragment.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function clear($key) {
    
        $file = $this->setCacheFileName($key);
        if (file_exists($file)) {
            
            unlink($file);
        
        }
        
        return false;
        
    }
    
}