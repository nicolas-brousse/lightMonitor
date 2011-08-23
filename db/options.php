<?php

define('APPLICATION_BASE_URI', dirname(__DIR__).'/');

require_once APPLICATION_BASE_URI.'/vendor/App.php';
require_once APPLICATION_BASE_URI.'/vendor/yaml/lib/sfYaml.php';

$config = App::loadConfigs(APPLICATION_BASE_URI . '/app/configs/', 'production');
$config->app['db']['path'] = APPLICATION_BASE_URI . $config->app['db']['path'];

return $config->app['db'];
