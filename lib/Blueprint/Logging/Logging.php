<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Blueprint\Logging;

/**
 * Logging class.
 */
class Logging {

    /**
     * log function.
     * 
     * @access public
     * @static
     * @param \Exception $e
     * @return void
     */
    public static function log(\Exception $e) {
    
        $error = 'Error on line ' . $e->getLine() . 
                  ' in ' . $e->getFile() . ': ' . $e->getMessage();
    
        switch (LOG_TYPE) {
        
            case 'output':
            
                echo $error;
                break;
                
            case 'file':
                
                $error = date('c', time()) . ': ' . $error . "\n";
                if (file_put_contents(VAR_DIR . 'log/error.log', $error, FILE_APPEND))
                    echo 'An error occurred. The error has been logged.';
                
                break;
                
            default:
                
                break;
        
        }
    
    }

}