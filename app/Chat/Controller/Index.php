<?php

namespace Chat\Controller;

use Blueprint\Controller\Controller;
use Chat\Form\MessageForm;
use Chat\View;

/**
 * Index class.
 * 
 * @extends Controller
 */
class Index extends Controller {

    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        
        $this->view = new View\Index();
        
    }
    
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
        
        $this->view->setContainer($this->container);
    
    }

    /**
     * indexAction function.
     * 
     * @access public
     * @return void
     */
    public function indexAction() {

        $this->form = new MessageForm();
        $vars['form'] = $this->form->render();
        echo $this->view->render("index.php", $vars);
    
    }

}