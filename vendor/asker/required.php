<?php

# Load Extension

namespace Asker;

use Silex\Application;
use Silex\ExtensionInterface;

class AskerExtension implements ExtensionInterface
{
  private $default_class_path = '/lib';

  public function register(Application $app)
  {
    $app[__NAMESPACE__] = true; /*$app->protect(function () use ($app) {
        return "Hello $name";
    });*/

    if (isset($app['asker.class_path']) AND is_dir($app['asker.class_path'])) {
      $dir = $app['asker.class_path'];
    }
    else {
      $dir = __DIR__ . $this->default_class_path;
    }
    $app['autoloader']->registerNamespace(__NAMESPACE__, $dir);
  }
}