<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Blueprint\View;

/**
 * Abstract View class.
 *
 * Base class from which all other view classes inherit.
 * 
 * @package blueprint
 * @abstract
 * @author Christopher <chris@jooldesign.co.uk>
 */
abstract class View {

    /**
     * container
     * 
     * @var mixed
     * @access protected
     */
    protected $container;
    
    /**
     * setContainer function.
     * 
     * @access public
     * @param mixed $container
     * @return void
     */
    public function setContainer($container) {
        
        $this->container = $container;

    }

    /**
     * render function.
     *
     * Calls a template, loads functions and injects variables into it.
     * 
     * @access public
     * @param mixed $template
     * @param bool $vars (default: false)
     * @return void
     */
    public function render($template, $vars=false) {
    
        if (is_array($vars))
            extract($vars);
        
        ob_start();
        if (file_exists(VIEW_DIR . 'functions.php'))
            include(VIEW_DIR . 'functions.php');
        
        require(VIEW_DIR . $template);
        
        return ob_get_clean();
    
    }
    
    /**
     * siteInfo function.
     *
     * Provides access to config variables.
     * 
     * @access public
     * @param mixed $key
     * @param string $default (default: '')
     * @return void
     */
    public function siteInfo($key, $default='') {
    
        $cfg = $this->container->get('config');
        $val = !empty($cfg->$key) ? $cfg->$key : $default;
        return $val;
    
    }
    
}