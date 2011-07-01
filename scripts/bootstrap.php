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
$app['monolog.level'] = Monolog\Logger::DEBUG;
$app->register(new Asker\AskerExtension());
$app->register(new Silex\Extension\SwiftmailerExtension(), array(
  'swiftmailer.class_path'  => __DIR__.'/../vendor/swiftmailer/lib',
));

# Ne peut être éxécuté que par un jobcron ?
if(!defined('STDIN') ) 
  die('This file must be execute in CLI mode');

#var_dump($argv);