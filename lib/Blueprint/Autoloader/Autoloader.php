<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Autoloader;

/**
 * Autoloader class.
 *
 * Provides a set of methods to register top level directories from which
 * to find classes. Classes are included only when they are called using
 * this method.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Autoloader {

    /**
     * load_paths
     * 
     * (default value: array())
     *
     * Stores the paths from which to find classes that need to be 
     * autoloaded.
     * 
     * @var array
     * @access private
     * @static
     */
    private static $load_paths = array();

    /**
     * registerAutoloadPath function.
     * 
     * Adds a new path to the the Autoloader.
     *
     * @access public
     * @static
     * @param mixed $path
     * @return void
     */
    public static function registerAutoloadPath($path) {

        self::$load_paths[] = $path;

    }

    /**
     * autoload function.
     *
     * Attempts to load a class file when it is instantiated.
     * 
     * @access public
     * @static
     * @param mixed $class_name
     * @return void
     */
    public static function autoload($class_name) {
    
        foreach (self::$load_paths as $load_path) {

            $path = self::pathToClass($load_path, $class_name);

            if ($path === null) 
                continue;
        
            require_once $path;
            return;
        
        }

    }

    /**
     * pathToClass function.
     *
     * Determines the path to the class file based of the fully 
     * qualified class name (including namespace)
     * 
     * @access private
     * @static
     * @param mixed $root
     * @param mixed $class_name
     * @return void
     */
    private static function pathToClass($root, $class_name) {
    
        $path = $root;
        
        $class_name = str_replace('\\', '/', $class_name);
        $path .= $class_name . '.php';
    
        if (file_exists($path))
            return $path;

        return null;
    
    }
    
}