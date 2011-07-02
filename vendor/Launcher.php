<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('APPLICATION_VERSION', 'alpha');

require_once __DIR__.'/silex.phar';
require_once __DIR__.'/App.php';
require_once __DIR__ . '/../app/bootstrap.php';