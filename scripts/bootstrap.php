<?php
/**
 *
 * Scripts Bootstrap
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

/**
 * APP CONST
 */
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
#App::autoload(APPLICATION_BASE_URI . '/app/controllers/helpers/');

/**
 * Initialize new Application
 */
App::setEnv(APPLICATION_ENV);
$app = App::getInstance('jobCron');
$config = App::configs();
$config->app['db']['path'] = APPLICATION_BASE_URI . $config->app['db']['path'];


/**
 *  Register Extensions
 */
$app->register(new Silex\Extension\MonologExtension(), array(
  'monolog.logfile'       => APPLICATION_BASE_URI.'/data/log/development.jobCron.log',
  'monolog.class_path'    => APPLICATION_BASE_URI.'/vendor/monolog/src',
  'monolog.name'          => 'jobCron',
));
$app['monolog.level'] = APPLICATION_ENV == 'development' ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;
$app['monolog']->addInfo("CLI EXEC : ".CLI_FILENAME);

$app->register(new Silex\Extension\DoctrineExtension(), array(
  'db.options'  => $config->app['db'],
  'db.dbal.class_path'    => APPLICATION_BASE_URI.'/vendor/doctrine2-dbal/lib',
  'db.common.class_path'  => APPLICATION_BASE_URI.'/vendor/doctrine2-common/lib',
));
// TODO verify if DB exist, else duplicate emptyDB

$app->register(new Asker\AskerExtension());

$app->register(new Silex\Extension\SwiftmailerExtension(), array(
  'swiftmailer.class_path'  => APPLICATION_BASE_URI.'/vendor/swiftmailer/lib',
));

