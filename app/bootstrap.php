<?php

if (DEBUG === true) {
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $mt = microtime(1);
    
}

use Blueprint\Autoloader\Autoloader;
require(LIB_DIR . 'Blueprint/Autoloader/Autoloader.php');

/*  
    All classes in /lib/ and /app/ will be automatically required
    by the autoloader upon instantiation
*/
Autoloader::registerAutoloadPath(LIB_DIR);
Autoloader::registerAutoloadPath(APP_DIR);
spl_autoload_register('Blueprint\Autoloader\Autoloader::autoload');

// Tell the application which namespaces when instantiating classes
use Blueprint\Logging\Logging;
use Blueprint\Container\Container;
use Blueprint\Config\Config;
use Chat\Database\MongoDatabase;
//use Blueprint\Session\Session;
use Blueprint\Page\Page;
use Blueprint\Router\Router;

// Log all uncaught exceptions
function exceptionHandler($e) {
    
    Logging::log($e);

}

set_exception_handler('exceptionHandler');

// Create a blank container to hold all dependencies for the application
class App extends Container {}
$app = new App();

// Start loading the mandatory classes and add to the container
$config = new Config();
$config->loadConfig(CONF_DIR . 'config.php'); // tell it where config file lives.
$app->set('config', $config);

$app->set('database', new MongoDatabase($app->get('config'))); // pass config dependency to the database
$app->set('page', new Page($app->get('config')));

// Pass the router all the core dependencies
$router = new Router($app);

// register the namespaces in /app/ (including regex routes if applicable)
$router->registerRouteMap('Chat');

// dispatch to controller
$router->route();

if (DEBUG === true) {

    echo '<br />Processed in ' . 
    round((microtime(1) - $mt), 3) . 
    ' seconds<br />' . 
    number_format(memory_get_peak_usage() / 1024, 0, ".", ",") . 
    " Kb's used";
    
}

?>