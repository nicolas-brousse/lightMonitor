<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('APPLICATION_MICROTIME_START', microtime(true));
define('APPLICATION_VERSION', 'alpha 0.3');
define('APPLICATION_BASE_URI', dirname(__DIR__).'/');

// ini set
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
ini_set('log_errors', true);
ini_set('html_errors', false);
ini_set('error_log', APPLICATION_BASE_URI . '/data/log/php.log');
ini_set('display_errors', APPLICATION_ENV == 'development' ? true : false);

// required
require_once APPLICATION_BASE_URI . '/vendor/silex.phar';
require_once APPLICATION_BASE_URI . '/vendor/App.php';
require_once APPLICATION_BASE_URI . '/app/bootstrap.php';
