<?php
/**
 *
 * Main application Bootstrap
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */


/**
 * Load Required files
 */
require_once APPLICATION_BASE_URI.'/vendor/rrdtool/required.php';
require_once APPLICATION_BASE_URI.'/vendor/asker/required.php';
require_once APPLICATION_BASE_URI.'/vendor/yaml/lib/sfYaml.php';


/**
 * Load Configs
 **/
$configs = App::loadConfigs(APPLICATION_BASE_URI . '/app/configs/', APPLICATION_ENV);


/**
 * Initialize App
 */
$app = App::getInstance();
$app['version'] = APPLICATION_VERSION;
$app['name'] = "LightMonitor";
$app['debug'] = APPLICATION_ENV == 'development' ? true : false;
$app['configs'] = $configs;


/**
 * Register Extensions
 */
$app->register(new Silex\Extension\TwigExtension(), array(
  'twig.path'       => APPLICATION_BASE_URI . '/app/views',
  'twig.class_path' => APPLICATION_BASE_URI . '/vendor/twig/lib',
));

$app->register(new Silex\Extension\SessionExtension());

$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => APPLICATION_BASE_URI . '/data/log/'.APPLICATION_ENV.'.App.log',
  'monolog.class_path'    => APPLICATION_BASE_URI . '/vendor/monolog/src',
  'monolog.name'          => 'App',
));
$app['monolog.level'] = APPLICATION_ENV == 'development' ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;

$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Rrdtool\RrdtoolExtension());

$app->register(new Asker\AskerExtension());

$app->register(new Silex\Extension\DoctrineExtension(), array(
  'db.options'  => array(
    'driver'    => 'pdo_sqlite',
    'path'      => APPLICATION_BASE_URI . '/db/light_monitor.sqlite',
  ),
  'db.dbal.class_path'    => APPLICATION_BASE_URI . '/vendor/doctrine2-dbal/lib',
  'db.common.class_path'  => APPLICATION_BASE_URI . '/vendor/doctrine2-common/lib',
));
// TODO verif if DB exist, else duplicate emptyDB


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
require_once APPLICATION_BASE_URI . '/app/routes.php';


/**
 * Run Application
 */
$app->run();
