<?php

/*
 * This file is part of the Blueprint Framwork package.
 *
 * (c) Christopher Briggs <chris@jooldesign.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blueprint\Router;

/**
 * Router class.
 *
 * Routes URLs to the correct controller class.
 *
 * @package blueprint
 * @author Christopher <chris@jooldesign.co.uk>
 */
class Router {

    /**
     * app
     * 
     * (default value: null)
     * 
     * @var mixed
     * @access protected
     */
    protected $app = null;
    
    /**
     * url
     * 
     * (default value: '')
     * 
     * @var string
     * @access protected
     */
    protected $url = '';
    
    /**
     * route_maps
     * 
     * (default value: array())
     * 
     * @var array
     * @access protected
     */
    protected $route_maps = array();
    
    /**
     * controller
     * 
     * @var mixed
     * @access protected
     */
    protected $controller_name = 'Index';
    
    /**
     * action
     * 
     * @var mixed
     * @access protected
     */
    protected $action_name = 'index';
    
    /**
     * contoller
     * 
     * @var mixed
     * @access protected
     */
    protected $contoller;
    
    /**
     * params
     * 
     * (default value: array())
     * 
     * @var array
     * @access protected
     */
    protected $params = array();

    /**
     * __construct function.
     *
     * Loads dependencies, determines URL to use to match routes
     * routes the URL.
     * 
     * @access public
     * @param mixed $app
     * @return void
     */
    public function __construct($app) {
        
        $this->app = $app;
        
        $cfg = $this->app->get('config');

        if (strpos($_SERVER['REQUEST_URI'], $cfg->base) === 0)
            $this->url = substr($_SERVER['REQUEST_URI'], strlen($cfg->base));

        $this->url = preg_replace('/\?.*$/', '', $this->url);
            
    }
    
    /**
     * registerRouteMap function.
     *
     * Registers a set of options to define what URL pattern matches 
     * a route to a contoller/action pair
     * 
     * @access public
     * @param mixed $namespace
     * @param array $routes (default: array())
     * @return void
     */
    public function registerRouteMap($namespace, $routes=array()) {

        $this->route_maps[] = array(
            'namespace'     => $namespace,
            'routes'        => $routes
        );
    
    }

    /**
     * explodeUrl function.
     *
     * Splits the URL by directory serparator.
     * 
     * @access protected
     * @return void
     */
    protected function explodeUrl() {

        $this->parts = !empty($this->url) ? explode('/', $this->url) : array();
    
        return $this;
  
    }

    /**
     * parseUrl function.
     *
     * Determines the contoller, action and paramaters from the URL parts.
     * 
     * @access protected
     * @return void
     */
    protected function parseUrl() {
    
        $controller_name = $this->controller_name;
        $action_name = $this->action_name;

        $this->params = array();

        $p = $this->parts;
        if (!empty($p[0]) && $p[0][0] != '?')
            $controller_name = $p[0];

        if (!empty($p[1]) && $p[1][0] != '?')
            $action_name = $p[1];

        if (!empty($p[2]))
            $this->params = array_slice($p, 2);
        
        return array(
            $controller_name,
            $action_name
        );
    
    }
    
    /**
     * determineToControllerName function.
     *
     * Determines the class name from URL part.
     * 
     * @access protected
     * @param mixed $controller_name
     * @return void
     */
    protected function determineToControllerName($controller_name) {
    
        return str_replace(
            " ", 
            "", 
            ucwords(str_replace("-", " ", $controller_name))
        );
    
    }
    
    /**
     * determineActionName function.
     *
     * Determines the action name from the URL part.
     * 
     * @access protected
     * @param mixed $action_name
     * @return void
     */
    protected function determineActionName($action_name) {
    
        $action_name = str_replace(
            " ", 
            "", 
            ucwords(str_replace("-", " ", $action_name))
        );
        
        return preg_replace_callback(
            '/^([A-Z])/', 
            function($matches) { return strtolower($matches[1]); }, 
            $action_name
        );
        
    }
    
    /**
     * routeByDefault function.
     *
     * The default method for routing a URL to a controller. The URL is split by 
     * directory separator to determine the controller name, action name and 
     * paramaters
     * 
     * @access protected
     * @param mixed $map
     * @return void
     */
    protected function routeByDefault($map) {
    
        $_route = $this->explodeUrl()->parseUrl();
        
        $controller_name = $this->determineToControllerName($_route[0]);
        $controller_name = $map['namespace'] . '\\Controller\\' . $controller_name;
                    
        if (!class_exists($controller_name))
            return false;
                
        $action_name = $this->determineActionName($_route[1]);
        $action_name = $action_name . 'Action';
        
        if (method_exists($controller_name, $action_name)) {
        
            $this->controller_name = $controller_name;
            $this->action_name = $action_name;
        
            return true;
            
        }
            
        return false;
    
    }
    
    /**
     * routeByRegex function.
     *
     * The alternative method to route a URL to a controller. The URL is matched 
     * against regular expressions in order to determine the controller name, action 
     * name and paramaters
     * 
     * @access protected
     * @param mixed $map
     * @return void
     */
    protected function routeByRegex($map) {
    
        if (!empty($map['routes'])) {
        
            $routes = include $map['routes'];
                
            $_route = array();
            foreach ($routes as $k => $route) {
                
                if (preg_match('/'.$route[0].'/', $this->url, $matches)) {
                    
                    $_route = $routes[$k];
                    array_shift($matches);
                    $this->params = $matches;
                    break;
                    
                }
            
            }
            
            if (empty($_route))
                return false;
            
            $controller_name = $map['namespace'] . '\\Controller\\' . $_route[1];
            
            if (!class_exists($controller_name))
                return false;
            
            $action_name = $_route[2] . 'Action';
            
            if (method_exists($controller_name, $action_name)) {
            
                $this->controller_name = $controller_name;
                $this->action_name = $action_name;
            
                return true;
                
            }
                
        }
        
        return false;
    
    }

    /**
     * route function.
     *
     * Tests the registered route maps until one matches. Then instantiates the 
     * controller, sets container and calls the action (with parameters). 
     *
     * @access public
     * @return void
     */
    public function route() {
    
        if (!empty($this->route_maps)) {
        
            foreach ($this->route_maps as $map) {
                
                if ($this->routeByRegex($map))
                    break;

                if ($this->routeByDefault($map))
                    break;
            
            }
            
            if (method_exists($this->controller_name, $this->action_name)) {
                
                $this->controller = new $this->controller_name;
                $this->controller->setContainer($this->app);
            
                call_user_func_array(array($this->controller, $this->action_name), $this->params);
                
            } else {
                
                header('HTTP/1.0 404 Not Found');
                header('Status: 404 Not Found');
                echo '<h1>404 Not Found</h1>';
                
            }
        
        } else {
        
            throw new \Exception('No route maps have been registered');
            
        }
    
    }

}