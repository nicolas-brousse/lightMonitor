<?php

namespace Controller;

use App;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Abstract Class Base
{
  protected $app;
  protected $twig;
  protected $db;
  protected $monolog;
  protected $url_generator;

  final public function __construct()
  {
    $this->app = App::getInstance();
    $this->twig = $this->app['twig'];
    $this->db = $this->app['db'];
    $this->monolog = $this->app['monolog'];
    #var_dump($this->app); exit;

    if (method_exists($this, 'init')) {
      $this->init();
    }
  }

  protected function _getRequest()
  {
    return $this->app['request'];
  }

  protected function _halt()
  {
    throw new NotFoundHttpException();
  }

  protected function _helper()
  {
    
  }

  protected function _redirector($url)
  {
    // TODO: dynamize
    if (preg_match('#^/#', $url))
      $url = substr($url, 1);
    return $this->app->redirect($this->app['configs']->app['app']['base_url'] . $url);
  }

  protected function _getUrl($bind, array $parameters=array())
  {
    # http://silex-project.org/doc/extensions/url_generator.html
    return $this->app['url_generator']->generate($bind, $parameters);
  }
}