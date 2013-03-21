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
 * FormField class.
 *
 * Provides a set of methods for building an HTML form field and validating it.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class FormField {
    
    /**
     * name
     * 
     * (default value: '')
     * 
     * @var string
     * @access public
     */
    public $name = '';
    
    /**
     * allowed_types
     * 
     * @var mixed
     * @access public
     */
    public $allowed_types = array(
    
        'text',
        'password',
        'hidden',
        'textarea',
        'checkbox',
        'radio',
        'select',
        'file',
        'image',
        'reset',
        'submit'
    
    );
    
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
     * options
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $options = array();
    
    /**
     * selected
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $selected = array();
    
    /**
     * label
     * 
     * (default value: '')
     * 
     * @var string
     * @access public
     */
    public $label = '';
    
    /**
     * validation
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $validation = array();
    
    /**
     * valid
     * 
     * (default value: true)
     * 
     * @var bool
     * @access public
     */
    public $valid = null;

    /**
     * __construct function.
     *
     * Sets attributes and options related to the form field.
     * 
     * @access public
     * @param mixed $type
     * @param mixed $name
     * @param bool $label (default: false)
     * @param array $attrs (default: array())
     * @param array $options (default: array())
     * @param array $pre_selected (default: array())
     * @return void
     */
    public function __construct($type, $name, $label=false, $attrs=array(), $options=array(), $pre_selected=array()) {
        
        $this->attrs['type'] = $type;
        $this->checkAllowedType();
        
        $this->name = $name;
        $this->attrs['name'] = $name;
        $this->label = !empty($label) ? $label : null;
            
        if (!empty($attrs)) {
    
            foreach ($attrs as $k => $v)
                $this->setAttr($k, $v);
                
        }
        
        if (!empty($options)) {
        
            foreach ($options as $k => $v)
                $this->setOption($k, $v);
        
        }
        
        if (!empty($pre_selected)) {
        
            foreach ($pre_selected as $k => $v)
                $this->setSelected($k);
        
        }
        
        // add type as a css class
        $class = $this->getAttr('class');
        $this->setAttr('class', trim(implode(' ', array($class, $this->attrs['type']))));
        
    }
    
    /**
     * checkAllowedType function.
     *
     * Checks that the field type is a valid field type.
     * 
     * @access private
     * @return void
     */
    private function checkAllowedType() {
    
        if (in_array($this->attrs['type'], $this->allowed_types))
            return true;
        else 
            throw new \Exception('Field type ('.$this->attrs['type'].') not recognised', 100);
    
    }
    
    /**
     * getAttr function.
     *
     * Gets an attribute of the form field.
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
     * Sets an attribute of the form field.
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
     * getValue function.
     *
     * Gets the value of the form field.
     * 
     * @access public
     * @return void
     */
    public function getValue() {
    
        return $this->attrs['value'];
    
    }
    
    /**
     * setValue function.
     *
     * Sets the value of the form field.
     * 
     * @access public
     * @param mixed $val
     * @return void
     */
    public function setValue($val) {
    
        $this->attrs['value'] = $val;
    
    }
    
    /**
     * getOption function.
     *
     * Gets an option of the form field.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function getOption($key) {
    
        return isset($this->options[$key]) ? $this->options[$key] : null;
    
    }
    
    /**
     * setOption function.
     *
     * Sets an option of the form field.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $val
     * @return void
     */
    public function setOption($key, $val) {
        
        $this->options[$key] = $val;
    
    }
    
    /**
     * isSelected function.
     *
     * Checks if an option is selecetd in the form field.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function isSelected($key) {
    
        if (in_array($key, $this->selected))
            return true;
        else
            return false;
        
    }
    
    /**
     * getSelected function.
     *
     * Gets the selected options of the form field.
     * 
     * @access public
     * @return void
     */
    public function getSelected() {
    
        return $this->selected;
    
    }
    
    /**
     * setSelected function.
     *
     * Sets an options as selected in the form field.
     * 
     * @access public
     * @param mixed $key
     * @return void
     */
    public function setSelected($key) {
        
        $this->selected[] = $key;
    
    }
    
    /**
     * setSubmittedValue function.
     *
     * Fills the form field value based on what was submitted.
     * 
     * @access public
     * @param mixed $value
     * @return void
     */
    public function setSubmittedValue($value) {
        
        if (in_array($this->attrs['type'], array('checkbox', 'radio', 'select'))) {
            
            $this->selected = array();
            if (is_array($value)) {
            
                foreach ($value as $v)
                    //$this->setSelected(array_search($v, $this->options));
                    $this->setSelected($v);

            } else {
            
                //$this->setSelected(array_search($value, $this->options));
                $this->setSelected($value);
            
            }
        
        } else {
        
            $this->setAttr('value', $value);
            
        }
    
    }
    
    /**
     * addValidation function.
     *
     * Creates a validation rule by instantiating a FormValidation object.
     *
     * @access public
     * @param mixed $rule
     * @param bool $options (default: false)
     * @param bool $message (default: false)
     * @return void
     */
    public function addValidation($rule, $options=false, $message=false) {
    
        $this->validation[] = new FormValidation($rule, $options, $message);
    
    }
    
    /**
     * validate function.
     *
     * Validates rules that have been applied to each form field.
     * 
     * @access public
     * @param mixed $value
     * @return void
     */
    public function validate($value) {
        
        if (!empty($this->validation)) {
        
            foreach ($this->validation as $validation) {
                
                $validation->validate($value);
                if (!$validation->isValid()) {
                
                    $this->valid = false;
                    $class = $this->getAttr('class');
                    $this->setAttr('class', implode(' ', array($class, 'error')));
                    
                }
                
            }
            
        }
        
        if ($this->valid !== false)
            $this->valid = true;
    
    }
    
    /**
     * isValid function.
     * 
     * Checks if the form field is valid.
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
     * getInvalidRules function.
     * 
     * Gets any rules that failed validation.
     * 
     * @access public
     * @return void
     */
    public function getInvalidRules() {
            
        $invalid_rules = array();
        if (!empty($this->validation)) {
        
            foreach ($this->validation as $validation) {
                
                if ($validation->valid === false)
                    $invalid_rules[] = $validation;
                    
            }
            
        }
        
        return $invalid_rules;
        
    }
    
    /**
     * renderAttrs function.
     *
     * Renders the attributes as HTML attributes to be injected into the
     * form field tag.
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
            
        return substr($attrs, 0, -1);
    
    }
    
    /**
     * renderField function.
     *
     * Renders the form field based on the field type.
     * 
     * @access public
     * @return void
     */
    public function renderField() {
        
        $field = '';
        switch ($this->attrs['type']) {
        
            case 'text':
                
                $field .= $this->renderTextField();
                break;
                
            case 'password':
                
                $field .= $this->renderPasswordField();
                break;
                
            case 'hidden':
                
                $field .= $this->renderHiddenField();
                break;
                
            case 'textarea':
                
                $field .= $this->renderTextAreaField();
                break;
                
            case 'checkbox':
                
                $field .= $this->renderCheckboxFields();
                break;
                
            case 'radio':
                
                $field .= $this->renderRadioFields();
                break;
                
            case 'select':
                
                $field .= $this->renderSelectField();
                break;
                
            case 'file':
            
                $field .= $this->renderFileField();
                break;
                
            case 'image':
                
                $field .= $this->renderImageField();
                break;
                
            case 'reset':
                
                $field .= $this->renderSubmitField();
                break;
                
            case 'submit':
                
                $field .= $this->renderSubmitField();
                break;
        
        }
        
        return $field;
    
    }
    
    /**
     * renderTextField function.
     *
     * Renders a text field.
     * 
     * @access private
     * @return void
     */
    private function renderTextField() {
        
        $field = '<input ';
        $field .= $this->renderAttrs();
        $field .= ' />'."\n";
        
        return $field;
    
    }
    
    /**
     * renderPasswordField function.
     *
     * Renders a password field.
     * 
     * @access private
     * @return void
     */
    private function renderPasswordField() {
    
        // same as renderTextField()
        return $this->renderTextField();
    
    }
    
    /**
     * renderHiddenField function.
     *
     * Renders as hidden field.
     * 
     * @access private
     * @return void
     */
    private function renderHiddenField() {
    
        // same as renderTextField()
        return $this->renderTextField();
    
    }
    
    /**
     * renderTextAreaField function.
     *
     * Renders a textarea field.
     * 
     * @access private
     * @return void
     */
    private function renderTextAreaField() {
        
        $field = '<textarea ';
        $field .= $this->renderAttrs(array('value', 'type')); // exclude value and type attribute
        $field .= '>';
        $field .= isset($this->attrs['value']) ? $this->attrs['value'] : '';
        $field .= '</textarea>'."\n";
        
        return $field;
    
    }
    
    /**
     * renderCheckboxFields function.
     *
     * Renders a set of checkbox fields.
     * 
     * @access private
     * @return void
     */
    private function renderCheckboxFields() {
        
        $field = '<ul class="field-options">';
        foreach ($this->options as $k => $v) {

            $field .= '<li>';
            $field .= '<label class="' . $this->attrs['type'] . '"><input ';
            $field .= $this->renderAttrs(array('value')); // exclude value attribute
            $field .= ' value="';
            $field .= $k;
            $field .= '"';
            $field .= $this->isSelected($k) ? ' checked="checked"' : '';
            $field .= ' /> '.$v.'</label></li>'."\n";
            
        }
        $field .= '</ul>';
        
        return $field;
    
    }
    
    /**
     * renderRadioFields function.
     *
     * Renders a set of radio fields.
     * 
     * @access private
     * @return void
     */
    private function renderRadioFields() {
    
        // same as renderCheckboxFields()
        return $this->renderCheckboxFields();
    
    }
    
    /**
     * renderSelectField function.
     *
     * Renders a select field.
     * 
     * @access private
     * @return void
     */
    private function renderSelectField() {
    
        $field = '<select ';
        $field .= $this->renderAttrs(array('value', 'type')); // exclude value and type attribute
        $field .= '>'."\n";
        
        foreach ($this->options as $k => $v) {
            
            $field .= '<option value="';
            $field .= $k;
            $field .= '"';
            $field .= $this->isSelected($k) ? ' selected="selected"' : '';
            $field .= '>';
            $field .= $v;
            $field .= '</option>'."\n";
            
        }
        
        $field .= '</select>'."\n";
        
        return $field;
    
    }
    
    /**
     * renderFileField function.
     * 
     * Renders a file upload field.
     * 
     * @access private
     * @return void
     */
    private function renderFileField() {
    
        // same as renderTextField()
        return $this->renderTextField();
    
    }
    
    /**
     * renderimageField function.
     *
     * Renders an image submit field.
     * 
     * @access private
     * @return void
     */
    private function renderimageField() {
    
        // same as renderTextField()
        return $this->renderTextField();
    
    }
    
    /**
     * renderResetField function.
     *
     * Renders a reset field.
     * 
     * @access private
     * @return void
     */
    private function renderResetField() {
    
        // same as renderTextField()
        return $this->renderTextField();
    
    }
    
    /**
     * renderSubmitField function.
     *
     * Renders a submit field.
     * 
     * @access private
     * @return void
     */
    private function renderSubmitField() {
    
        // same as renderTextField()
        return $this->renderTextField();
    
    }
    
    /**
     * renderLabel function.
     *
     * Renders a label for the form field.
     * 
     * @access public
     * @return void
     */
    public function renderLabel() {
        
        $label = '';
        if (!empty($this->label)) {

            $label .= '<label for="' . $this->name . '"';
            $label .= in_array($this->attrs['type'], array('checkbox', 'radio')) ? ' class="group-label"' : '';
            $label .= '>' . $this->label;
            
            if (!empty($this->validation)) {
                foreach ($this->validation as $validation) {
                    if ($validation->rule == 'required') {
                        $label .= '<span class="red">*</span>'; 
                        break;
                    }
                }
            }
            
            $label .= '</label>'."\n";
            
        }
        
        return $label;
        
    }
    
    /**
     * renderMessage function.
     *
     * Renders any validation failures.
     * 
     * @access public
     * @return void
     */
    public function renderMessage() {
        
        $message = '';
        
        if ($this->valid === null)
            return '';
        
        if (!empty($this->validation)) {

            $message .= '<span class="help-inline">';
            foreach ($this->validation as $rule) {

                if ($rule->valid === false)
                    $message .= $rule->message.'. ';
                    
            }
            $message .= '</span>';
                    
        }
        
        return $message;
    
    }
    
    /**
     * render function.
     *
     * Render a label, field and messages.
     * 
     * @access public
     * @return void
     */
    public function render() {
    
        $field = '<div class="control-group';
        if (!$this->isValid() && $this->valid !== null)
            $field .= ' error';
            
        $field .= '">';
        
        $field .= $this->renderLabel();
        $field .= $this->renderField();

        $field .= $this->renderMessage();
        $field .= '</div>';
         
        return $field;
            
    }

}