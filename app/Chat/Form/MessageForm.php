<?php

namespace Chat\Form;

use Blueprint\Form\Form;
use Blueprint\Form\FormField;

/**
 * MessageForm class.
 * 
 * @extends Form
 */
class MessageForm extends Form {

    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct('', 'post', array('id' => 'form', 'class' => 'form-inline'));
        
        $this->buildForm();
        
    }
    
    /**
     * buildForm function.
     * 
     * @access private
     * @return void
     */
    private function buildForm() {
        
        $this->username = new FormField('text', 'username');
        $this->username->setAttr('placeholder', 'Choose a username');
        $this->username->setAttr('id', 'username');

        $this->message = new FormField('text', 'message');
        $this->message->setAttr('placeholder', 'Your message');
        $this->message->setAttr('class', 'span8');
        $this->message->setAttr('id', 'message');
    
        $this->submit = new FormField('submit', 'submit');
        $this->submit->setValue('Send');
        $this->submit->setAttr('class', 'btn btn-primary');
    
    }

    /**
     * render function.
     * 
     * @access private
     * @return string
     */
    public function render() {

        $form = '';
        $form .= $this->renderFormStartTag();
        
        $form .= $this->username->renderField() . "\n";
        $form .= $this->message->renderField() . "\n";
        
        $form .= $this->submit->renderField() . "\n";
    
        $form .= $this->renderFormEndTag();
        
        return $form;

    }

}