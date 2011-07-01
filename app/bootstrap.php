<?php


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
/*
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
*/

require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/App.php';
require_once __DIR__.'/../vendor/rrdtool/required.php';
require_once __DIR__.'/../vendor/asker/required.php';
require_once __DIR__.'/../vendor/yaml/lib/sfYaml.php';


# Load Configs
$configs = App::loadConfigs(__DIR__ . '/configs/');

# App
$app = App::getApp();

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
$app['monolog.level'] = APPLICATION_ENV == 'development' ? Monolog\Logger::DEBUG : Monolog\Loger::WARNING;

$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Rrdtool\RrdtoolExtension());
$app->register(new Asker\AskerExtension());

/*$app->register(new Silex\Extension\DoctrineExtension(), array(
    'db.options'  => array(
        'driver'    => 'pdo_sqlite',
        'path'      => __DIR__.'/../data/db',
    ),
));

$sql = "SELECT * FROM posts WHERE id = ?";
$post = $app['db']->fetchAssoc($sql, array(1));
var_dump($post);*/

# Autoload Configs
# functionAutoload($dir, $ext=array('yml'))


# Autoload Controllers
App::autoload(__DIR__ . '/controllers/');


# Routes
require_once __DIR__ . '/routes.php';

# Run
$app->run();