<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Blueprint\Form;

/**
 * Form class.
 *
 * Provides a set of methods for building an HTML form and validating it.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Form {
    
    /**
     * handler
     * 
     * @var mixed
     * @access public
     */
    public $handler;
    
    /**
     * method
     * 
     * (default value: 'post')
     * 
     * @var string
     * @access public
     */
    public $method = 'post';
    
    /**
     * attrs
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $attrs = array();
    
    /**
     * valid
     * 
     * (default value: null)
     * 
     * @var mixed
     * @access public
     */
    public $valid = null;

    /**
     * __construct function.
     *
     * Sets the form handler, method and attributes.
     * 
     * @access public
     * @param string $handler (default: '')
     * @param string $method (default: 'post')
     * @param array $attrs (default: array())
     * @return void
     */
    public function __construct($handler='', $method='post', $attrs=array()) {
        
        $this->handler = $handler;
        $this->method = $method;
        
        if (!empty($attrs)) {
    
            foreach ($attrs as $k => $v)
                $this->setAttr($k, $v);
                
        }
    
    }
    
    /**
     * isSubmitted function.
     *
     * Checks if the form has been submitted (and optionally checks that a 
     * specifed submit variable has been passed).
     * 
     * @access public
     * @param bool $submit (default: false)
     * @return void
     */
    public function isSubmitted($submit=false) {
    
        $values = $this->method == 'post' ? $_POST : $_GET;
    
        if (empty($submit) && !empty($values) || 
            !empty($submit) && !empty($values[$submit]))
            return true;
        else
            return false;
            
    }
    
    /**
     * getAttr function.
     *
     * Gets an attribute of the form.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function getAttr($key) {
    
        return isset($this->attrs[$key]) ? $this->attrs[$key] : null;
    
    }
    
    /**
     * setAttr function.
     *
     * Sets an attribute of the form.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $val
     * @return void
     */
    public function setAttr($key, $val) {
        
        $this->attrs[$key] = $val;
    
    }
    
    /**
     * renderAttrs function.
     *
     * Renders the attributes as HTML attributes to be injected into the
     * open form tag.
     * 
     * @access private
     * @param array $exclude (default: array())
     * @return void
     */
    private function renderAttrs($exclude=array()) {
        
        $attrs = '';
        foreach ($this->attrs as $k => $v) {
            
            if (!in_array($k, $exclude))
                $attrs .= $k.'="'.$v.'" ';
                
        }
            
        return $attrs;
    
    }
    
    /**
     * addFields function.
     *
     * Creates fields by instantiating FormField objects and mapping them
     * as properties of the Form object.
     * 
     * @access public
     * @param array $fields (default: array())
     * @return void
     */
    public function addFields($fields=array()) {
    
        if (!empty($fields)) {
    
            foreach ($fields as $k => $v) {
                
                // bit messy but it's the only way to do it
                @list($arg_1, $arg_2, $arg_3, $arg_4, $arg_5, $arg_6) = $v;
                $this->$k = new FormField($arg_1, $arg_2, $arg_3, $arg_4, $arg_5, $arg_6);
                
            }
                
        }
        
    }
    
    /**
     * getFields function.
     *
     * Gets all the defined FormField objects stored in the Form object.
     * 
     * @access private
     * @return void
     */
    private function getFields() {
    
        $fields = get_object_vars($this);
        unset($fields['handler'], $fields['method'], $fields['attrs'], $fields['valid']);
    
        return $fields;
    
    }
    
    /**
     * addNonceField function.
     * 
     * @access public
     * @return void
     */
    public function addNonceField() {
    
    }
    
    /**
     * getRecursiveArrayValue function.
     *
     * Iterates over an array to get the value from the final index
     * 
     * @access private
     * @param mixed $array
     * @param mixed $indexes
     * @return void
     */
    private function getRecursiveArrayValue($array, $indexes) {
    
        if (empty($array[$indexes[0]]))
            return false;
       
        if (count($indexes) == 1) {
            
            if ($indexes[0] === 0)
                return $array;

            return $array[$indexes[0]];
                
        } else {
            
            $index = array_shift($indexes);
            return $this->getRecursiveArrayValue($array[$index], $indexes);
            
        }
        
    }
    
    /**
     * determineFieldValue function.
     *
     * Finds the value from the GET/POST string for a given field
     * 
     * @access public
     * @param mixed $field
     * @return void
     */
    public function determineFieldValue($field) {
       
        $values = $this->method == 'post' ? $_POST : $_GET;
       
        if (preg_match_all('/\[(.*?)\]/', $field->name, $matches)) {
           
            $indexes = array(0 => preg_replace('/(.*?)\[.*/', '$1', $field->name));
            foreach($matches[1] as $match) {
               
                if ($match == null)
                    $match = 0;
               
                    $indexes[] = $match;
           
            }
       
            $val = $this->getRecursiveArrayValue($values, $indexes);
            
            return isset($val) ? $val : null;
           
        } else {
           
            return isset($values[$field->name]) ? $values[$field->name] : null;
       
        }
    
    }
    
    /**
     * validate function.
     *
     * Validates rules that have been applied to each FormField object.
     * 
     * @access public
     * @return void
     */
    public function validate() {
    
        $fields = $this->getFields();        
        foreach ($fields as $field) {
            
            $val = $this->determineFieldValue($field);
            
            $field->validate($val);
            
            if (!$field->isValid())
                $this->valid = false;
            
        }
        
        if ($this->valid !== false)
            $this->valid = true;
    
    }
    
    /**
     * isValid function.
     * 
     * Checks if the form is valid.
     * 
     * @access public
     * @return void
     */
    public function isValid() {
    
        if ($this->valid === true)
            return true;
            
        return false;
    
    }
    
    /**
     * getInvalidFields function.
     *
     * Gets any FormField objects that failed validation.
     * 
     * @access public
     * @return void
     */
    public function getInvalidFields() {
            
        $invalid_fields = array();
        
        $fields = $this->getFields();
        if (!empty($fields)) {
        
            foreach ($fields as $field) {
                
                if ($field->valid === false)
                    $invalid_fields[] = $field;
                    
            }
            
        }
        
        return $invalid_fields;
        
    }
    
    /**
     * setSubmittedValues function.
     *
     * Fills the form field values based on what was submitted.
     * 
     * @access public
     * @return void
     */
    public function setSubmittedValues() {
        
        $fields = $this->getFields();
        foreach ($fields as $field) {
            
            if ($this->determineFieldValue($field) == null) {
            
                $field->setSubmittedValue(null);
                
            } else {        
            
                $field->setSubmittedValue($this->determineFieldValue($field));
            
            }
        
        }

    }
    
    /**
     * renderFormStartTag function.
     *
     * Renders the HTML opening form tag.
     * 
     * @access public
     * @return void
     */
    public function renderFormStartTag() {
        
        $form = '<form ';
        $form .= $this->renderAttrs(array('action', 'method')); // exclude action and method attribute
        $form .= 'action="'.$this->handler.'" method="'.$this->method.'">'."\n\n";
        
        return $form;
    
    }
    
    /**
     * renderFormEndTag function.
     *
     * Renders the HTML closing form tag.
     * 
     * @access public
     * @return void
     */
    public function renderFormEndTag() {
        
        return '</form>'."\n";
        
    }
    
    /**
     * renderMessages function.
     *
     * Renders any vaildation failures.
     * 
     * @access public
     * @return void
     */
    public function renderMessages() {
        
        $message = '';
        
        if ($this->valid === null)
            return '';
            
        $fields = $this->getInvalidFields();
        
        if (!empty($fields)) {
            
            $message .= '<ul>'."\n";
            foreach ($fields as $field) {
                
                $message .= '<li>'.$field->label.'</li>'."\n";
                $message .= '<ul>'."\n";
                if (!empty($field->validation)) {
    
                    foreach ($field->validation as $rule) {
    
                        if ($rule->valid === false)
                            $message .= '<li>'.$rule->message.'</li>'."\n";
                        
                    }
                    
                }
                $message .= '</ul>';
                        
            }
            $message .= '</ul>';
            
            
        }
        
        return $message;
    
    }
    
    /**
     * render function.
     *
     * Renders the form.
     * 
     * @access public
     * @return void
     */
    public function render() {
    
        $form = '';
        $form .= $this->renderFormStartTag();
        
        $fields = $this->getFields();
        foreach ($fields as $field) {
            
            if (!in_array($field->attrs['type'], array('hidden', 'reset', 'submit'))) {
            
                $form .= $field->render() . "\n";
                
            }
                
        }
        
        foreach ($fields as $field) {
            
            if (in_array($field->attrs['type'], array('hidden', 'reset', 'submit')))  
                $form .= $field->render() . "\n";
                
        }
            
        $form .= $this->renderFormEndTag();
        
        return $form;
    
    }

}