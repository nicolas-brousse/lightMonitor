<?php 

require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/rrdtool/required.php';
require_once __DIR__.'/../vendor/asker/required.php';

$app = new Silex\Application();

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
