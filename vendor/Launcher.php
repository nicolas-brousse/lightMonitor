<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('APPLICATION_VERSION', 'alpha');
define('APPLICATION_BASE_URI', dirname(__DIR__).'/');

require_once APPLICATION_BASE_URI . '/vendor/silex.phar';
require_once APPLICATION_BASE_URI . 'vendor/App.php';
require_once APPLICATION_BASE_URI . '/app/bootstrap.php';
