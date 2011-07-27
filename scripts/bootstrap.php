<?php
/**
 *
 * Scripts Bootstrap
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */


/**
 * Options detection
 */
if ('cli' === php_sapi_name()) {
  if (empty($argv[1]) OR empty($argv[2])) {
    print("Precise environment\nExample file.php -e development\n");
    exit(0);
  }
  switch ($argv[1]) {
    case '-e':
      if (in_array($argv[2], array('development', 'production'))) {
        define('APPLICATION_ENV', $argv[2]);
        define('CLI_FILENAME', basename($argv[0]));
      }
      else {
        printf("Unkown environment '%s' (available: development or production).\n", $argv[1]);
        exit(0);
      }
      break;

    default:
      printf("Unkown option '%s' (available commands: -e).\n", $argv[1]);
      exit(0);
  }
  $argv = array();
}
else {
  die('This file must be execute in CLI mode');
}

/**
 * Load required files
 */
require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/App.php';
require_once __DIR__.'/../vendor/yaml/lib/sfYaml.php';
require_once __DIR__.'/../vendor/rrdtool/required.php';
require_once __DIR__.'/../vendor/asker/required.php';
require_once __DIR__.'/../vendor/swiftmailer/lib/swift_required.php';

/**
 * Initialize new Application
 */
$app = App::getInstance('jobCron');
$configs = App::loadConfigs(__DIR__ . '/../app/configs/', APPLICATION_ENV);


/**
 *  Register Extensions
 */
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => __DIR__.'/../data/log/development.jobCron.log',
  'monolog.class_path'    => __DIR__.'/../vendor/monolog/src',
  'monolog.name'          => 'jobCron',
));
$app['monolog.level'] = APPLICATION_ENV == 'development' ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;
$app['monolog']->addInfo("CLI EXEC : ".CLI_FILENAME);

$app->register(new Silex\Extension\DoctrineExtension(), array(
  'db.options'  => array(
    'driver'    => 'pdo_sqlite',
    'path'      => __DIR__.'/../data/db/light_monitor.sqlite',
  ),
  'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine2-dbal/lib',
  'db.common.class_path'  => __DIR__.'/../vendor/doctrine2-common/lib',
));

$app->register(new Asker\AskerExtension());

$app->register(new Silex\Extension\SwiftmailerExtension(), array(
  'swiftmailer.class_path'  => __DIR__.'/../vendor/swiftmailer/lib',
));

