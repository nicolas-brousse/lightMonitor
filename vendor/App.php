<?php

Class App
{
  protected static $app = array();

  public static function getInstance($name="default")
  {
    if (!isset(self::$app[$name])) {
      self::$app[$name] = new Silex\Application();
    }
    return self::$app[$name];
  }

  public static function autoload($path)
  {
    if (is_dir($path)) {
      $queue = array();
      if ($dh = opendir($path)) {
        $yamlLoader = new sfYaml();
        while (($file = readdir($dh)) !== false) {
          if (is_file($path.$file) AND !preg_match('#^\.#', $file)) {
            if (preg_match('#base\.php#i', $file))  { require_once $path.$file; }
            else                                   { $queue[] = $file; }
          }
        }
        closedir($dh);
      }

      foreach ($queue as $file) {
        require_once $path.$file;
      }
    }
    else {
      throw new Exception('ERROR : \''.$path.'\' missing !');
    }
  }

  public static function loadConfigs($path, $env='production')
  {
    $configs = new stdClass();

    if (is_dir($path)) {
      if ($dh = opendir($path)) {
        $yamlLoader = new sfYaml();
        while (($file = readdir($dh)) !== false) {
          if (preg_match('#.yml$#i', $file) AND !preg_match('#^\.#', $file)) {
            $tmp = $yamlLoader->load($path.$file);
            if (!isset($tmp[$env])) {
              throw new Exception("ERROR: Environment '".$env."' not exist in '".$file."' file");
            }
            $configs->{substr($file, 0, -4)} = $tmp[$env];
          }
        }
        closedir($dh);
      }
    }
    else {
      throw new Exception('ERROR : \''.$path.'\' missing !');
    }
    return $configs;
  }
}