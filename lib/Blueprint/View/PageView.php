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
 * PageView class.
 *
 * Provides a set of methods for rendering a web page.
 * 
 * @package blueprint
 * @extends View
 * @author Christopher <chris@jooldesign.co.uk>
 */
class PageView extends View {

    /**
     * page
     * 
     * @var mixed
     * @access protected
     */
    protected $page;
    
    /**
     * setContainer function.
     * 
     * @access public
     * @param mixed $container
     * @return void
     */
    public function setContainer($container) {
    
        parent::setContainer($container);

        $this->page = $this->container->get('page');

    }

    /**
     * pageInfo function.
     *
     * Provides access to page properties.
     * 
     * @access public
     * @param mixed $key
     * @param string $default (default: '')
     * @return void
     */
    public function pageInfo($key, $default='') {
    
        $val = !empty($this->page->$key) ? $this->page->$key : $default;
        return $val;
    
    }
    
    /**
     * getCss function.
     *
     * Renders the HTML for all the registered CSS files.
     * 
     * @access public
     * @return void
     */
    public function getCss() {
    
        $html = '';
        foreach ($this->page->css as $css)
            $html .= '<link rel="stylesheet" type="text/css" href="' . $css . '" />' . "\n";

        return $html;
    
    }
    
    /**
     * getJs function.
     *
     * Renders the HTML for all the registered JavaScript files.
     * 
     * @access public
     * @return void
     */
    public function getJs() {
        
        $html = '';
        foreach ($this->page->js as $js)
            $html .= '<script type="text/javascript" src="' . $js . '"></script>' . "\n";        
            
        return $html;
    }
    
}