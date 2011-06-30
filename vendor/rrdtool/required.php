<?php

# Load Extension

namespace Asker;

use Silex\Application;
use Silex\ExtensionInterface;

class AskerExtension implements ExtensionInterface
{
  private $default_class_path = '/vendor/rrdtool/lib';
  private $extension_name = 'Asker';

  public function register(Application $app)
  {
    $app[$this->extension_name] = $app->protect(function () use ($app) {
        return "Hello $name";
    });

    $app['autoloader']->registerNamespace($this->extension_name, isset($app['rrdtool.class_path']) ? $app['rrdtool.class_path'] : $this->default_class_path);
  }
}
