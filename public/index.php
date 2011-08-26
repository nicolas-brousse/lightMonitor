<?php # Lauch app

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

require_once __DIR__ . '/../vendor/Launcher.php';

// TODO, realise a setup exe
# http://www.switchonthecode.com/tutorials/php-tutorial-creating-and-modifying-sqlite-databases