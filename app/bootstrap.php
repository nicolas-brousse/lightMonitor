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
  'monolog.logfile'       => __DIR__.'/../data/log/development.App.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
  'monolog.name'          => 'App',
));
$app['monolog.level'] = Monolog\Logger::DEBUG;

$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Rrdtool\RrdtoolExtension());
$app->register(new Asker\AskerExtension());

# Load Configs
$config = new stdClass();
$dir = __DIR__ . "/configs/";
if (is_dir($dir)) {
  if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
      if (preg_match('#.yml$#i', $file)) {
        #$loader = new Symfony\Component\Routing\Loader\YamlFileLoader(); $loader->load($file);
        $config->tmp[$file] = file_get_contents($dir.$file);
      }
    }
    closedir($dh);
  }
}
else {
  throw new Exception('ERROR : /app/configs missing !');
}


# Autoload Configs
# functionAutoload($dir, $ext=array('yml'))

# Autoload Controllers
# functionAutoload($dir, $ext=false)


# Routes
require_once __DIR__ . '/routes.php';

# Run
$app->run();