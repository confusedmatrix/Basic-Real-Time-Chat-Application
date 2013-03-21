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
class PageCaching extends Caching {

    /**
     * cache_file
     * 
     * @var mixed
     * @access public
     */
    public $cache_file;
    
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
        
        $this->status = $this->config->defaults['page_caching']['status'];
        $this->expiry = $this->config->defaults['page_caching']['expiry'];
    
    }
    
    /**
     * startCachingFile function.
     *
     * Determines the cache file name, starts output buffer and outputs cached
     * file if it exists and hasn't yet expired.
     * 
     * @access public
     * @return void
     */
    public function startCachingFile() {
        
        $filename = str_replace("/", "", $_SERVER["REQUEST_URI"]);
        $this->cache_file = $this->cache_dir . $filename . ".cache";
        
        try {
                
            if ($this->status === true && empty($_POST)) {
                    
                ob_start("ob_gzhandler");
                
                if (file_exists($this->cache_file) && ((time() - $this->expiry) < filemtime($this->cache_file))) {

                    echo file_get_contents($this->cache_file);
                    exit;

                }
            
            }
            
        } catch (Exception $e) {
            
            echo 'Could not begin caching file using function beginCachingFile\n
                    The following exception was given: ' . $e;
            
            exit;
                    
        }
    
    }
    
    /**
     * stopCachingFile function.
     *
     * Turns output buffering off and saves output to cache file before outputting 
     * to page
     * 
     * @access public
     * @return void
     */
    public function stopCachingFile() {
        
        try {
        
            if ($this->status === true && empty($_POST)) {
                
                file_put_contents($this->cache_file, ob_get_contents());
                ob_end_flush();
                
            }
            
        } catch (Exception $e) {
            
            echo "Could not stop caching file using function stopCachingFile\n
                    The following exception was given: ".$e;
            
            exit;
            
        }
    
    }
    
}