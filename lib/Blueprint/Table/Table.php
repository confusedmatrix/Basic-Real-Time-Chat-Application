<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Table;

/**
 * Table class.
 *
 * Provides a set of methods for building tables from a data set
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Table {

    /**
     * columns
     * 
     * (default value: array())
     * 
     * @var array
     * @access protected
     */
    protected $columns = array();
    
    /**
     * data
     * 
     * (default value: array())
     * 
     * @var array
     * @access protected
     */
    protected $data = array();
    
    /**
     * actions
     * 
     * @var mixed
     * @access protected
     */
    protected $actions = array();

    /**
     * __construct function.
     * 
     * @access public
     * @param mixed $data
     * @param array $attrs (default: array())
     * @return void
     */
    public function __construct($data, $columns=array(), $attrs=array()) {
    
        $this->data = $data;
    
        if (!empty($attrs)) {
    
            foreach ($attrs as $k => $v)
                $this->setAttr($k, $v);
                
        }
        
        $this->setColumnData($columns);
    
    }
    
    /**
     * getAttr function.
     *
     * Gets an attribute of the table.
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
     * Sets an attribute of the table.
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
     * open table tag.
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
     * setColumnData function.
     * 
     * @access public
     * @param array $columns (default: array())
     * @return void
     */
    public function setColumnData($columns=array()) {
    
        $this->columns = $columns;
    
    }
    
    /**
     * addAction function.
     * 
     * @access public
     * @param mixed $name
     * @param mixed $link
     * @param string $class (default: '')
     * @param mixed $attrs
     * @param mixed $action_ids
     * @return void
     */
    public function addAction($name, $link, $class='', $attrs=array(), $action_ids=false) {
    
        $this->actions[] = array(
        
            'name'  => $name,
            'link'  => $link,
            'class' => $class,
            'attrs' => $attrs,
            'ids'   => $action_ids
        
        );
    
    }
    
    /**
     * renderAction function.
     * 
     * @access public
     * @param mixed $action
     * @param mixed $row_data
     * @return void
     */
    public function renderAction($action, $row_data) {
    
        $html = '<a class="btn ';
        $html .= $action['class'];
        $html .= '" href="';
        
        if (preg_match_all('/\{(.*?)\}/', $action['link'], $matches)) {
        
            foreach ($matches[1] as $key)
                $action['link'] = preg_replace('/\{' . $key . '\}/', $row_data[$key], $action['link']);
        
        }
    
        $html .= $action['link'];
        $html .= '"';
        foreach ($action['attrs'] as $k => $v) {
            
            if (preg_match_all('/\{(.*?)\}/', $v, $matches))
                foreach ($matches[1] as $key)
                    $v = preg_replace('/\{' . $key . '\}/', $row_data[$key], $v);

            $html .= ' ' . $k . '="' . $v . '"';

        }
        
        $html .= '>';
        $html .= $action['name'];
        $html .= '</a> ';
        
        return $html;
    
    }

    /**
     * render function.
     * 
     * @access public
     * @return void
     */
    public function render() {
    
        if (empty($this->data)) {
            
            return '<div class="alert alert-error">There are no records to show.</div>';
        
        } else {
            
            $html = '<table ';
            $html .= $this->renderAttrs();
            $html .= '>';

            $html .= '<thead>';
            $html .= '<tr>';
    
            foreach ($this->columns as $field => $field_name)
                $html .= '<th>' . $field_name . '</th>';
            
            if (!empty($this->actions))
                $html .= '<th>Actions</th>';
                
            $html .= '</tr>';
            $html .= '</thead>';

            $html .= '<tbody>';
            
            foreach ($this->data as $key => $row) {
                
                $html .= '<tr>';
                foreach ($this->columns as $field => $field_name)
                    $html .= '<td>' . $row[$field] . '</td>';
                
                if (!empty($this->actions)) {
    
                    $html .= '<td class="table-actions">';
                    foreach ($this->actions as $action) {
                        
                        if (!is_array($action['ids']) || (in_array($key, $action['ids'])))
                            $html .= $this->renderAction($action, $row);

                    }

                    $html .= '</td>';
                    
                }
                
                $html .= '</tr>';
            
            }
            
            $html .= '</tbody>';
            $html .= '</table>';
        
        }
        
        return $html;
    
    }
    
}