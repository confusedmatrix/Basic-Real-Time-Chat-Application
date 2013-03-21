<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Controller;

/**
 * Abstract Controller class.
 *
 * Base class from which all other controller classes inherit.
 * 
 * @package blueprint
 * @abstract
 * @author Christopher <chris@jooldesign.co.uk>
 */
abstract class Controller {

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
    
}