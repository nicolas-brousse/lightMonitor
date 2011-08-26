<?php
define('APPLICATION_MICROTIME_START', microtime(true));
define('APPLICATION_BASE_URI', dirname(__DIR__).'/');

// ini set
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
ini_set('log_errors', true);
ini_set('html_errors', true);
ini_set('error_log', APPLICATION_BASE_URI . '/data/log/php.log');
ini_set('display_errors', APPLICATION_ENV == 'development' ? true : false);

// autoload
/*function __autoload($classname)
{
  
}*/

// required
require_once APPLICATION_BASE_URI . '/vendor/App.php';
require_once APPLICATION_BASE_URI . '/app/bootstrap.php';
