<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Page;

/**
 * Page class.
 *
 * Provides a set of methods for managing page variables that can be accessed
 * in the view.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Page {

    /**
     * config
     * 
     * @var mixed
     * @access protected
     */
    protected $config;

    /**
     * title
     * 
     * @var mixed
     * @access public
     */
    public $title;
    
    /**
     * meta_description
     * 
     * @var mixed
     * @access public
     */
    public $meta_description;
    
    /**
     * h1
     * 
     * @var mixed
     * @access public
     */
    public $h1;
    
    /**
     * css
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $css = array();
    
    /**
     * js
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $js = array();
    
    /**
     * __construct function.
     * 
     * Loads dependencies and sets page variables.
     * 
     * @access public
     * @param mixed $config
     * @return void
     */
    public function __construct($config) {

        $this->config = $config;
        
        $this->title = $this->config->defaults['page']['title'];
        $this->meta_description = $this->config->defaults['page']['meta-description'];
        $this->h1 = $this->config->defaults['page']['h1'];
        
        foreach ($this->config->defaults['page']['css'] as $css)
            $this->setCss($css);
            
        foreach ($this->config->defaults['page']['js'] as $js)
            $this->setJs($js);
        
    }
    
    /**
     * setCss function.
     *
     * Sets a CSS file as a page variable.
     * 
     * @access public
     * @param mixed $file
     * @return void
     */
    public function setCss($file) {
        
        $this->css[] = $file;
        
    }
    
    /**
     * unsetCss function.
     *
     * Removes a CSS file from the page variables.
     * 
     * @access public
     * @param mixed $file
     * @return void
     */
    public function unsetCss($file) {
    
        if ($key = array_search($file, $this->css))
            unset($this->css[$key]);
    
    }
    
    /**
     * setJs function.
     *
     * Sets a JavaScript file as a page variable.
     * 
     * @access public
     * @param mixed $file
     * @return void
     */
    public function setJs($file) {
    
        $this->js[] = $file;
    
    }
    
    /**
     * unsetJs function.
     *
     * Removeds a JavaScript file from the page variables.
     * 
     * @access public
     * @param mixed $file
     * @return void
     */
    public function unsetJs($file) {
    
        if ($key = array_search($file, $this->file))
            unset($this->css[$key]);
    
    }

}