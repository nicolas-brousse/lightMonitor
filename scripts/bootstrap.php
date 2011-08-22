<?php
/**
 *
 * Scripts Bootstrap
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */


/**
 * Verif CLI options
 */
if ('cli' === php_sapi_name()) {
  if (empty($_SERVER['argv'][1]) OR empty($_SERVER['argv'][2])) {
    print("Precise environment\nExample file.php -e development\n");
    exit(0);
  }
  switch ($_SERVER['argv'][1]) {
    case '-e':
      if (in_array($_SERVER['argv'][2], array('development', 'production'))) {
        define('APPLICATION_ENV', $_SERVER['argv'][2]);
        define('CLI_FILENAME', basename($_SERVER['argv'][0]));
      }
      else {
        printf("Unkown environment '%s' (available: development or production).\n", $_SERVER['argv'][1]);
        exit(0);
      }
      break;

    default:
      printf("Unkown option '%s' (available commands: -e).\n", $_SERVER['argv'][1]);
      exit(0);
  }
}
else {
  die('This file must be execute in CLI mode');
}

define('APPLICATION_BASE_URI', dirname(__DIR__).'/');

/**
 * Load required files
 */
require_once APPLICATION_BASE_URI.'/vendor/silex.phar';
require_once APPLICATION_BASE_URI.'/vendor/App.php';
require_once APPLICATION_BASE_URI.'/vendor/yaml/lib/sfYaml.php';
require_once APPLICATION_BASE_URI.'/vendor/rrdtool/required.php';
require_once APPLICATION_BASE_URI.'/vendor/asker/required.php';
require_once APPLICATION_BASE_URI.'/vendor/swiftmailer/lib/swift_required.php';
App::autoload(APPLICATION_BASE_URI . '/app/controllers/helpers/');

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
// TODO verify if DB exist, else duplicate emptyDB

$app->register(new Asker\AskerExtension());

$app->register(new Silex\Extension\SwiftmailerExtension(), array(
  'swiftmailer.class_path'  => __DIR__.'/../vendor/swiftmailer/lib',
));

