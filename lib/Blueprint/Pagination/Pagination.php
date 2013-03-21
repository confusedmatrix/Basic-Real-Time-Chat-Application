<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Pagination;

/**
 * Pagination class.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Pagination {
    
    /**
     * page
     * 
     * (default value: 1)
     * 
     * @var int
     * @access public
     */
    public $page = 1;
    
    /**
     * start
     * 
     * (default value: 0)
     * 
     * @var int
     * @access public
     */
    public $start = 0;
    
    /**
     * length
     * 
     * (default value: 10)
     * 
     * @var int
     * @access public
     */
    public $length = 10;
    
    /**
     * num_records
     * 
     * @var mixed
     * @access public
     */
    public $num_records;

    /**
     * num_pages
     * 
     * @var mixed
     */
    public $num_pages;
    
    /**
     * __construct function.
     * 
     * @access public
     * @param mixed $page
     * @param mixed $length
     * @return void
     */
    public function __construct($page, $length, $num_records) {
        
        $this->page = $page;
        
        $this->start = ($this->page - 1) * $length;
        $this->length = $length;
        $this->num_records = $num_records;
        $this->num_pages = $this->getNumPages();
        
    }
    
    /**
     * getNumPages function.
     * 
     * @access public
     * @return void
     */
    public function getNumPages() {
    
        $num_pages =  ceil($this->num_records / $this->length);
        return $num_pages;
    
    }
    
    /**
     * determineBaseUrl function.
     * 
     * @access protected
     * @return void
     */
    protected function determineBaseUrl() {

        return preg_replace('/(\/page)?\/([0-9]+)(\/?)$/', '$3', $_SERVER['REQUEST_URI']) . 'page/';
        
    }
    
    /**
     * renderPagination function.
     * 
     * @access public
     * @return void
     */
    public function renderPagination() {
        
        if ($this->num_pages <= 1)
            return '';
    
        $request_uri = $this->determineBaseUrl();
        
        $high = $this->page + 4;
        $low = $this->page - 4;
            
        if ($high > $this->num_pages) {
        
            $high = $this->num_pages;
            $low = $this->num_pages - 8;
            
        }
        
        if ($low < 1) {
            
            $high = $this->num_pages < 9 ? $this->num_pages : 9;
            $low = 1;
            
        }
        
        $html = '<div class="pagination">';
        $html .= '</ul>';
        
        // start link
        $html .= '<li';
        if ($this->page == 1)
            $html .= ' class="disabled"';
            
        $html .= '><a';
        if ($this->page != 1)
            $html .= ' href="' . $request_uri . '1"';
            
        $html .= '>first</a></li>';

        // prev link
        $html .= '<li';
        if ($this->page == 1)
            $html .= ' class="disabled"';
            
        $html .= '><a';
        if ($this->page != 1)
            $html .= ' href="' . $request_uri . ($this->page - 1) . '"';
            
        $html .= '>prev</a></li>';
        
        // numbered links
        for ($i = $low; $i <= $high; $i++) {
            
            $html .= '<li';
            if ($this->page == $i)
                $html .= ' class="active"';
                
            $html .= '><a';
            
            if ($this->page != $i)
                $html .= ' href="' . $request_uri . $i . '"';
                    
            $html .= '>' . $i . '</a>';
            $html .= '</li>';
        
        }

        // next link
        $html .= '<li';
        if ($this->page >= $this->num_pages)
            $html .= ' class="disabled"';
        
        $html .= '><a';
        if ($this->page < $this->num_pages)
            $html .= ' href="' . $request_uri . ($this->page + 1) . '"';

        $html .= '>next</a></li>';
        
        // end link
        $html .= '<li';
        if ($this->page >= $this->num_pages)
            $html .= ' class="disabled"';
        
        $html .= '><a';
        if ($this->page < $this->num_pages)
            $html .= ' href="' . $request_uri . $this->num_pages . '"';

        $html .= '>last</a></li>';
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    
    }
    
    /**
     * renderPager function.
     * 
     * @access public
     * @return void
     */
    public function renderPager() {

        if ($this->num_pages <= 1)
            return '';

        $request_uri = $this->determineBaseUrl();

        $html .= '<ul class="pager">';

        // prev link
        $html .= '<li';
        if ($this->page == 1)
            $html .= ' class="disabled"';
            
        $html .= '><a';
        if ($this->page != 1)
            $html .= ' href="' . $request_uri . ($this->page - 1) . '"';
            
        $html .= '>prev</a></li>' . "\n";

        // next link
        $html .= '<li';
        if ($this->page >= $this->num_pages)
            $html .= ' class="disabled"';
        
        $html .= '><a';
        if ($this->page < $this->num_pages)
            $html .= ' href="' . $request_uri . ($this->page + 1) . '"';

        $html .= '>next</a></li>';

        $html .= '</ul>';

        return $html;
    
    }
    
}