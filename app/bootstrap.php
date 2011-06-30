<?php

require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/rrdtool/required.php';
require_once __DIR__.'/../vendor/asker/required.php';

$app = new Silex\Application();

# Register Extensions
$app->register(new Silex\Extension\TwigExtension(), array(
  'twig.path'       => __DIR__.'/views',
  'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => __DIR__.'/../data/log/development.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
  'monolog.name'          => 'App',
));
$app['monolog.level'] = Monolog\Logger::DEBUG;
$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Rrdtool\RrdtoolExtension());
$app->register(new Asker\AskerExtension());

# Serveurs config
$config = new stdClass();
#$config->servers = new Symfony\Component\Routing\Loader\YamlFileLoader();
$config->generals = (file_get_contents( __DIR__ . '/configs/app.yml'));
$config->servers = (file_get_contents( __DIR__ . '/configs/servers.yml'));



# Routes
require_once __DIR__ . '/routes.php';

# Run
$app->run();