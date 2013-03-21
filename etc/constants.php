<?php

// PATHS
define('BASE_DIR', $base.'/');
define('CONF_DIR', BASE_DIR . 'etc/');
define('LIB_DIR', BASE_DIR . 'lib/');
define('APP_DIR', BASE_DIR . 'app/');
define('VIEW_DIR', BASE_DIR . 'out/');
define('TMP_DIR', BASE_DIR . 'tmp/');
define('VAR_DIR', BASE_DIR . 'var/');

// CACHING
define('PAGE_CACHE_DIR', TMP_DIR . 'page-cache/');
define('FRAGMENT_CACHE_DIR', TMP_DIR . 'fragment-cache/');

// DEBUG
define('DEBUG', false);
define('LOG_TYPE', 'output'); // options are 'output', 'file' or 'none';

?>