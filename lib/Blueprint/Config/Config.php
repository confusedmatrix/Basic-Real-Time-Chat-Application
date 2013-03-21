<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Config;

/**
 * Config class.
 *
 * Holds all the config variables needed for the application.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Config {

    /**
     * loadConfig function.
     *
     * Loads config from an array of variables.
     * 
     * @access public
     * @param mixed $config_location
     * @return void
     */
    public function loadConfig($config_location) {
        
        try {
        
            $config = include $config_location;
            foreach ($config as $k => $v)
                $this->$k = $v;
                        
        } catch (\Exception $e) {
        
            echo 'An error occurred while loading the config. Please check that the format is correct.\n
                    The following exception was given ' . $e;
                    
            exit;
        
        }
    
    }

}