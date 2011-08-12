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

  final public function __construct()
  {
    $this->app = App::getInstance();
    $this->twig = $this->app['twig'];
    $this->db = $this->app['db'];
    $this->monolog = $this->app['monolog'];

    if (method_exists($this, 'init')) {
      $this->init();
    }
  }

  protected function _getRequest()
  {
    return $this->app['request'];
  }

  protected function _getPost($string=null)
  {
    if (!empty($string)) {
      return $this->_getRequest()->request->get($string);
    }
    else {
      return $this->_getRequest()->request->all();
    }
  }

  protected function _getGet($string=null)
  {
    if (!empty($string)) {
      return $this->_getRequest()->query->get($string);
    }
    else {
      return $this->_getRequest()->query->all();
    }
  }

  protected function _getSession()
  {
    return $this->app['session'];
  }

  protected function _halt()
  {
    throw new NotFoundHttpException();
  }

  protected function _helper()
  {
    // TODO autoload helpers
  }

  protected function _redirector($url)
  {
    return $this->app->redirect($url);
  }

  protected function _getUrl($bind, array $parameters=array())
  {
    return $this->app['url_generator']->generate($bind, $parameters);
  }
}