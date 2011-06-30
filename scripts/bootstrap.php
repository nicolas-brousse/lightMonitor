<?php 

require_once __DIR__.'/vendor/silex.phar';

$app = new Silex\Application();

# Register Extensions
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => __DIR__.'/../data/log/development.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
  'monolog.level'         => Logger::DEBUG,
  'monolog.name'          => 'jobCron',
));
$app->register(new Asker\AskerExtension());

# Ne peut être éxécuté que par un jobcron ?