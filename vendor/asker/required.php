<?php

# Load Extension

namespace Rrdtool;

use Silex\Application;
use Silex\ExtensionInterface;

class RrdtoolExtension implements ExtensionInterface
{
  private $default_class_path = '/vendor/rrdtool/lib';
  private $extension_name = 'Rrdtool';

  public function register(Application $app)
  {
    $app[$this->extension_name] = true; /*$app->protect(function () use ($app) {
        return "Hello $name";
    });*/

    $app['autoloader']->registerNamespace($this->extension_name, isset($app['rrdtool.class_path']) ? $app['rrdtool.class_path'] : $this->default_class_path);
  }
}