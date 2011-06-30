<?php 

require_once __DIR__.'/../vendor/silex.phar';

$app = new Silex\Application();

# Register
$app->register(new Silex\Extension\TwigExtension(), array(
  'twig.path'       => __DIR__.'/views',
  'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => __DIR__.'/../data/log/development.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
));
$app->register(new Silex\Extension\UrlGeneratorExtension());


# Serveurs config
$config = new stdClass();
#$config->servers = new Symfony\Component\Routing\Loader\YamlFileLoader();
$config->generals = (file_get_contents( __DIR__ . '/configs/app.yml'));
$config->servers = (file_get_contents( __DIR__ . '/configs/servers.yml'));



# Routes

# require_once 'app/controllers/index.php';
# $app->get('/', Index_Controller::Index_Action())->bind('homepage');


$app->get('/', function () use($app) {
  return $app['twig']->render('index/index.html.php', array());
})
->bind('homepage');

$app->get('/servers/{servername}', function ($servername) use ($app) {
  $servername = $app['request']->get('servername');
  return $app['twig']->render('servers/details.html.php', array());
})
->bind('servers');


$app->get('/configs', function () use ($app) {
  return $app['twig']->render('configs/index.html.php', array());
})
->bind('configs');

# Run
$app->run();