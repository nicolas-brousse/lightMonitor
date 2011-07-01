<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

# Requires
require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/App.php';
require_once __DIR__.'/../vendor/rrdtool/required.php';
require_once __DIR__.'/../vendor/asker/required.php';
require_once __DIR__.'/../vendor/yaml/lib/sfYaml.php';


# Load Configs
$configs = App::loadConfigs(__DIR__ . '/configs/');

# App
$app = App::getApp();
$app['version'] = "alpha1";

# Register Extensions
$app->register(new Silex\Extension\TwigExtension(), array(
  'twig.path'       => __DIR__.'/views',
  'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => __DIR__.'/../data/log/development.App.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
  'monolog.name'          => 'App',
));
$app['monolog.level'] = APPLICATION_ENV == 'development' ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;

$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Rrdtool\RrdtoolExtension());
$app->register(new Asker\AskerExtension());

$app->register(new Silex\Extension\DoctrineExtension(), array(
  'db.options'  => array(
    'driver'    => 'pdo_sqlite',
    'path'      => __DIR__.'/../data/db/light_monitor.sqlite',
  ),
  'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine2-dbal/lib',
  'db.common.class_path'  => __DIR__.'/../vendor/doctrine2-common/lib',
));

$servers = array();
foreach ($app['db']->fetchAll("SELECT servername, ip FROM servers") as $server) {
  $servers[] = array(
    'name' => $server['servername'],
    'bind'  => 'servers',
    'value'  => $server['ip'],
  );
}
# Navigation
$app['navigation'] = array(
  array(
    'name' => 'Dashboard',
    'href'  => '/',
    'bind'  => 'homepage',
  ),
  array(
    'name' => 'Serveurs',
    'href'  => '#',
    'bind'  => 'servers',
    'param' => 'ip',
    'items' => $servers,
  ),
  array(
    'name' => 'Configurations',
    'href'  => 'configs',
    'bind'  => 'configs',
  ),
);


# Routes
require_once __DIR__ . '/routes.php';

# Run
$app->run();
