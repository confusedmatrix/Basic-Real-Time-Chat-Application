<?php

namespace Chat\Controller;

use Blueprint\Controller\Controller;
use Chat\Model;
use Chat\View;

/**
 * Messages class.
 * 
 * @extends Controller
 */
class Messages extends Controller {

    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        
        $this->model = new Model\Messages();
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
        
        $this->model->setContainer($this->container);
        $this->view->setContainer($this->container);
    
    }

    /**
     * messagesAction function.
     * 
     * @access public
     * @return void
     */
    public function indexAction() {
        
        header('Content-type: application/json');
        $vars['json'] = $this->model->getMessagesAsJSON();
        echo $this->view->render('ajax.php', $vars);
    
    }

    /**
     * addMessageAction function.
     * 
     * @access public
     * @return void
     */
    public function addMessageAction() {
        
        $this->model->addMessage();
    
    }

    /**
     * clearMessagesAction function.
     * 
     * @access public
     * @return void
     */
    public function clearMessagesAction() {
        
        $this->model->clearMessages();
    
    }    

}