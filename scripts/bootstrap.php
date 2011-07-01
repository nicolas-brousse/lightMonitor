<?php 

require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/App.php';
require_once __DIR__.'/../vendor/yaml/lib/sfYaml.php';
require_once __DIR__.'/../vendor/rrdtool/required.php';
require_once __DIR__.'/../vendor/asker/required.php';

$app = App::getApp('jobCron');
$configs = App::loadConfigs(__DIR__ . '/../app/configs/');

# Register Extensions
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => __DIR__.'/../data/log/development.jobCron.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
  'monolog.name'          => 'jobCron',
));

$app->register(new Silex\Extension\DoctrineExtension(), array(
  'db.options'  => array(
    'driver'    => 'pdo_sqlite',
    'path'      => __DIR__.'/../data/db/light_monitor.sqlite',
  ),
  'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine2-dbal/lib',
  'db.common.class_path'  => __DIR__.'/../vendor/doctrine2-common/lib',
));

$app->register(new Asker\AskerExtension());
$app->register(new Silex\Extension\SwiftmailerExtension(), array(
  'swiftmailer.class_path'  => __DIR__.'/../vendor/swiftmailer/lib',
));

# Verif CLI
if(!defined('STDIN') ) 
  die('This file must be execute in CLI mode');

# Verif Environment
$app['monolog.level'] = APPLICATION_ENV == 'development' ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;
#var_dump($argv);
