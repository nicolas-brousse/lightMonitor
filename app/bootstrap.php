<?php
/**
 *
 * Main application Bootstrap
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */


/**
 * Initialize App
 */
$app = App::getInstance();
App::setEnv(APPLICATION_ENV);
$app['version'] = App::VERSION;
$app['name'] = App::NAME;
$app['debug'] = App::getEnv() == 'development' ? true : false;
$app['config'] = App::configs();
$app['config']->app['db']['path'] = APPLICATION_BASE_URI . $app['config']->app['db']['path'];


/**
 * Load Required files
 */
require_once APPLICATION_BASE_URI.'/vendor/rrdtool/required.php';
require_once APPLICATION_BASE_URI.'/vendor/asker/required.php';
require_once APPLICATION_BASE_URI.'/vendor/yaml/lib/sfYaml.php';


/**
 * Register Namespaces to autoloader
 */
App::autoload(__DIR__ . '/models/');
App::autoload(__DIR__ . '/controllers/');
#$app['autoloader']->registerNamespace('Model', APPLICATION_BASE_URI);
#$app['autoloader']->registerNamespace('Controller', APPLICATION_BASE_URI);


/**
 * Register Extensions
 */
$app->register(new Silex\Extension\TwigExtension(), array(
  'twig.path'       => APPLICATION_BASE_URI . '/app/views',
  'twig.class_path' => APPLICATION_BASE_URI . '/vendor/twig/lib',
));

$app->register(new Silex\Extension\SessionExtension());

$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => APPLICATION_BASE_URI . '/data/log/'.App::getEnv().'.App.log',
  'monolog.class_path'    => APPLICATION_BASE_URI . '/vendor/monolog/src',
  'monolog.name'          => 'App',
));
$app['monolog.level'] = App::getEnv() == 'development' ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;

$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Asker\AskerExtension());

$app->register(new Rrdtool\RrdtoolExtension());

$app->register(new Silex\Extension\DoctrineExtension(), array(
  'db.options'  => $app['config']->app['db'],
  'db.dbal.class_path'    => APPLICATION_BASE_URI . '/vendor/doctrine2-dbal/lib',
  'db.common.class_path'  => APPLICATION_BASE_URI . '/vendor/doctrine2-common/lib',
));


/**
 * Navigation
 */
$servers = array();
foreach ($app['db']->fetchAll("SELECT servername, ip FROM servers") as $server) {
  $servers[] = array(
    'name' => $server['ip'].' - '.$server['servername'],
    'bind'  => 'servers',
    'value'  => $server['ip'],
  );
}
$app['navigation'] = array(
  array(
    'name' => 'Dashboard',
    'href'  => '/',
    'bind'  => 'homepage',
  ),
  array(
    'name' => 'Servers',
    'href'  => '#',
    'bind'  => 'servers',
    'param' => 'ip',
    'items' => $servers,
  ),
  array(
    'name' => 'Settings',
    'href'  => '#',
    'bind'  => 'settings.servers',
    'items' => array(
      array(
        'name' => 'Servers',
        'bind' => 'settings.servers',
      ),
      array(
        'name' => 'Users',
        'bind' => 'settings.users',
      ),
    ),
  ),
);


/**
 * Load routes
 */
require_once __DIR__ . '/routes.php';


/**
 * Run Application
 */
$app->run();
