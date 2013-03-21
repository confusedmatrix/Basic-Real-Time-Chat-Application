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
 * FormValidation class.
 *
 * Provides a set of methods for creating a validation rule and validating
 * against it.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class FormValidation {

    /**
     * rule
     * 
     * @var mixed
     * @access public
     */
    public $rule;
    
    /**
     * options
     * 
     * @var mixed
     * @access public
     */
    public $options;
    
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
     * message
     * 
     * (default value: '')
     * 
     * @var string
     * @access public
     */
    public $message = '';
    
    /**
     * allowed_rules
     * 
     * @var mixed
     * @access public
     */
    public $allowed_rules = array(
    
        'required',
        'equal_to',
        'less_than',
        'greater_than',
        'between',
        'shorter_than',
        'longer_than',
        'length_between',
        'date',
        'numeric',
        'alphabetic',
        'alphanumeric',
        'email',
        'website',
        'uri',
        'postcode',
        'custom'
    
    );

    /**
     * __construct function.
     *
     * Sets the varlidation rule, options and message to dislpay on failure.
     * 
     * @access public
     * @param mixed $rule
     * @param bool $options (default: false)
     * @param bool $message (default: false)
     * @return void
     */
    public function __construct($rule, $options=false, $message=false) {
    
        $this->rule = $rule;
        $this->checkAllowedRule();
        $this->options = $options;
        $this->valid = null;
        $this->message = $message;
    
    }
    
    /**
     * checkAllowedRule function.
     *
     * Checks whether the rule is a valid rule.
     * 
     * @access private
     * @return void
     */
    private function checkAllowedRule() {
    
        if (in_array($this->rule, $this->allowed_rules))
            return true;
        else
            throw new Exception('Validation rule ('.$this->rule.') not recognised', 100);
    
    }
    
    /**
     * isValid function.
     *
     * Checks if the rule has be passes validation.
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
     * isRequired function.
     *
     * Checks if the a value is not empty.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @return void
     */
    public static function isRequired($value) {
    
        if (!empty($value)) 
            return true;
            
        return false;
    
    }
    
    /**
     * isEqualTo function.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $comparison
     * @return void
     */
    public static function isEqualTo($value, $comparison) {
    
        if ($value === $comparison)
            return true;
            
        return false;
    
    }
    
    /**
     * isLessThan function.
     *
     * Checks if a value is less than $max.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $max
     * @return void
     */
    public static function isLessThan($value, $max) {
    
        if (empty($value) || ($this->isNumeric($value) && $value < $max))
            return true;
            
        return false;
    
    }
    
    /**
     * isGreaterThan function.
     *
     * Checks if a value is greater than $min.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $min
     * @return void
     */
    public static function isGreaterThan($value, $min) {
    
        if (empty($value) || ($this->isNumeric($value) && $value > $min))
            return true;
            
        return false;
    
    }
    
    /**
     * isBetween function.
     *
     * Checks if a value is between $min and $max (inclusive).
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return void
     */
    public static function isBetween($value, $min, $max) {
    
        if (empty($value) || ($this->isNumeric($value) && $value >= $min && $value <= $max))
            return true;
            
        return false;
    
    }
    
    /**
     * isShorterThan function.
     *
     * Checks if a value is shorter in character length than $max.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $max
     * @return void
     */
    public static function isShorterThan($value, $max) {
    
        if (empty($value) || strlen($value) < $max)
            return true;
            
        return false;
    
    }
    
    /**
     * isLongerThan function.
     *
     * Checks if a value is longer in character length than $min.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $min
     * @return void
     */
    public static function isLongerThan($value, $min) {
    
        if (empty($value) || strlen($value) > $min)
            return true;
            
        return false;
    
    }

    /**
     * isLengthBetween function.
     *
     * Checks if a value is between $min and $max by number of characters 
     * (inclusive).
     * 
     * @access public
     * @static
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return void
     */
    public static function isLengthBetween($value, $min, $max) {
        
        $length = strlen($value);
        if (empty($value) || ($length >= $min && $length <= $max))
            return true;
            
        return false;
    
    }
    
    /**
     * isDate function.
     *
     * Checks if a value is date.
     * 
     * @access public
     * @static
     * @param mixed $date
     * @return void
     */
    public static function isDate($date) {
    
        if (empty($date) || strtotime($date))
            return true;
            
        return false;
    
    }
    
    /**
     * isNumeric function.
     *
     * Checks if a value is numeric.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @return void
     */
    public static function isNumeric($value) {
    
        if (empty($value) || is_numeric($value))
            return true;
            
        return false;
    
    }
    
    /**
     * isAlphabetic function.
     *
     * Checks if a value is alphabetic.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @return void
     */
    public static function isAlphabetic($value) {
    
        if (empty($value) || ctype_alpha($value))
            return true;
            
        return false;
    
    }
    
    /**
     * isAlphaNumeric function.
     *
     * Checks if a value is alphanumeric.
     * 
     * @access public
     * @static
     * @param mixed $value
     * @return void
     */
    public static function isAlphaNumeric($value) {
    
        if (empty($value) || ctype_alnum($value))
            return true;
            
        return false;
    
    }
    
    /**
     * isEmail function.
     *
     * Checks if a value is an email.
     * 
     * @access public
     * @static
     * @param mixed $email
     * @return void
     */
    public static function isEmail($email) {
    
        if (empty($email) || preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email))
            return true;
            
        return false;
    
    }
    
    /**
     * isWebsite function.
     *
     * Checks if a value is a website.
     * 
     * @access public
     * @static
     * @param mixed $website
     * @return void
     */
    public static function isWebsite($website) {
    
        if (empty($website) || preg_match('/^[A-Z0-9-]+\.[A-Z0-9-]+/i', $website)) // needs work.
            return true;
            
        return false;
    
    }
    
    /**
     * isURI function.
     *
     * Checks is a value is a URI.
     * 
     * @access public
     * @static
     * @param mixed $uri
     * @return void
     */
    public static function isURI($uri) {
    
        if (empty($uri) || preg_match('/^[^\s:\/?#]+:(?:\/{2,3})?[^\s.\/?#]+(?:\.[^\s.\/?#]+)*(?:\/[^\s?#]*\??[^\s?#]*(#[^\s#]*)?)?$/i', $uri))
            return true;
            
        return false;
    
    }
    
    /**
     * isPostcode function.
     *
     * Checks if a value is a postcode.
     * 
     * @access public
     * @static
     * @param mixed $postcode
     * @return void
     */
    public static function isPostcode($postcode) {
    
        if (empty($postcode) || preg_match('/[A-Z]{1,2}[0-9]{1,2}[A-Z]?\s?[0-9][A-Z]{2}/i', $postcode))
            return true;
            
        return false;
    
    }
    
    public static function checkCustomError($bool) {
    
        if (empty($bool) || $bool === true)
            return true;
            
        return false;
    
    }
    
    /**
     * validate function.
     *
     * Validates the rule against an appropriate function.
     * 
     * @access public
     * @param mixed $value
     * @return void
     */
    public function validate($value) {
    
        switch ($this->rule) {
        
            case 'required':
                
                $this->valid = FormValidation::isRequired($value);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be empty';
                break;
            
            case 'equal_to':
                
                $this->valid = FormValidation::isEqualTo($value, $this->options);
                $this->message = !empty($this->message) ? $this->message : 'Field must be equal to '.($this->options);
                break;
                    
            case 'less_than':
                
                $this->valid = FormValidation::isLessThan($value, $this->options);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be greater than '.($this->options-1);
                break;
                
            case 'greater_than':
            
                $this->valid = FormValidation::isGreaterThan($value, $this->options);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be less than '.($this->options+1);
                break;
            
            case 'between':
            
                $this->valid = FormValidation::isBetween($value, $this->options[0], $this->options[1]);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be less than '.($this->options[0]).' or greater than '.($this->options[1]);
                break;
                
            case 'shorter_than':
                
                $this->valid = FormValidation::isShorterThan($value, $this->options);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be longer than '.($this->options-1).' characters';
                break;

            case 'longer_than':
                
                $this->valid = FormValidation::isLongerThan($value, $this->options);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be shorter than '.($this->options+1).' characters';
                break;
            
            case 'length_between':
            
                $this->valid = FormValidation::isLengthBetween($value, $this->options[0], $this->options[1]);
                $this->message = !empty($this->message) ? $this->message : 'Field cannot be shorter than '.($this->options[0]).' or longer than '.($this->options[1]).' characters';
                break;
            
            case 'date':
                
                $this->valid = FormValidation::isDate($value);
                $this->message = !empty($this->message) ? $this->message : 'Not a valid date';
                break;
                
            case 'numeric':
                
                $this->valid = FormValidation::isNumeric($value);
                $this->message = !empty($this->message) ? $this->message : 'Field must contain numbers only';
                break;
                
            case 'alphabetic':
                
                $this->valid = FormValidation::isAlphabetic($value);
                $this->message = !empty($this->message) ? $this->message : 'Field must contain letters only';
                break;
                
            case 'alphanumeric':
            
                $this->valid = FormValidation::isAlphaNumeric($value);
                $this->message = !empty($this->message) ? $this->message : 'Field must contain numbers and letters only';
                break;
                
            case 'email':
                
                $this->valid = FormValidation::isEmail($value);
                $this->message = !empty($this->message) ? $this->message : 'Not a valid email';
                break;
                
            case 'website':
                
                $this->valid = FormValidation::isWebsite($value);
                $this->message = !empty($this->message) ? $this->message : 'Not a valid website';
                break;
                
            case 'uri':
            
                $this->valid = FormValidation::isURI($value);
                $this->message = !empty($this->message) ? $this->message : 'Not a valid URI';
                break;
                
            case 'postcode':
            
                $this->valid = FormValidation::isPostcode($value);
                $this->message = !empty($this->message) ? $this->message : 'Not a valid postcode';
                break;
                
            case 'custom':
                
                $this->valid = FormValidation::checkCustomError($this->options);
                $this->message = !empty($this->message) ? $this->message : 'Error';
                break;
        
        }
    
    }

}