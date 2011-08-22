<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

# Autoloader controllers
App::autoload(__DIR__ . '/controllers/');
//$controllers = array();
//foreach (App::autoload(__DIR__ . '/controllers/') as $controller) {
//  $classname=substr($controller,0,-4).'_Controller';
//  $controllers[strtolower(substr($controller,0,-4))] = new $classname();
//}

// TODO: use PHP5 autoloader


/**
 * ROUTES
 **/

$app->get('/',          function () { $c = new Controller\Index(); return $c->Index_Action(); })->bind('homepage');
$app->get('/log',       function () { $c = new Controller\Index(); return $c->Log_Action(); })->bind('log');
#$app->get('/dashboard', function () use($app) { return $app->redirect('/'); });



$app->get('/servers/{ip}',  function () { $c = new Controller\Server(); return $c->Index_Action(); })->bind('servers');



$app->get('/settings/servers',         function () { $c = new Controller\Setting\Server(); return $c->Index_Action(); })->bind('settings.servers');
$app->get('/settings/servers/new',     function () { $c = new Controller\Setting\Server(); return $c->New_Action(); })->bind('settings.servers.new');
$app->post('/settings/servers/save',   function () { $c = new Controller\Setting\Server(); return $c->Save_Action(); })->bind('settings.servers.save');
$app->get('/settings/edit/{ip}',       function () { $c = new Controller\Setting\Server(); return $c->Edit_Action(); })->bind('settings.servers.edit');
$app->post('/settings/update/{ip}',    function () { $c = new Controller\Setting\Server(); return $c->Update_Action(); })->bind('settings.servers.update');
$app->get('/settings/delete/{ip}',     function () { $c = new Controller\Setting\Server(); return $c->Delete_Action(); })->bind('settings.servers.delete');

$app->get('/settings/users',           function () { $c = new Controller\Setting\User(); return $c->Index_Action(); })->bind('settings.users');


/** SESSION **/
$app->get('/login', function() use ($app) {
  $username = $app['request']->server->get('PHP_AUTH_USER', false);
  $password = $app['request']->server->get('PHP_AUTH_PW');

  if ('admin' === $username && 'admin' === $password) {
      $app['session']->set('user', array('username' => $username));
      // var_dump($app['session']->get('user')); exit;
      return $app->redirect($app['url_generator']->generate('homepage'));
  }

  $response = new Response();
  $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', $app['name']));
  $response->setStatusCode(401, 'Please sign in.');
  return $response;
})->bind('app.login');
/**/


/**
 * Errors and Favicon
 */

$app->get('/favicon.{ext}', function($ext) use ($app) {
  $state = array_rand(array('on' => true, 'off' => true));
  return new Response(
          file_get_contents(APPLICATION_BASE_URI . 'public/img/icons/'.($ext == 'ico' ? 'favicon_' : '').$state.'.'.$ext),
          200,
          array(
            'Content-Type' => ($ext == 'ico' ? 'image/x-icon' : 'image/png'),
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => ' Sat, 26 Jul 1997 05:00:00 GMT',
          )
      );
})
->assert('ext', '(png|ico)');


/**
 * FILTERS
 */
$app->error(function(\Exception $e, $code) use ($app) {
    if ($e instanceof NotFoundHttpException) {
        $content = array(
          'title' => '404 Not Found',
          'message' => '<h1>Error '.$e->getStatusCode().'</h1> <pre>'.Response::$statusTexts[$e->getStatusCode()].' ('.$app['request']->getRequestUri().')</pre>',
        );
    }
    else if ($e instanceof HttpException) {
      $content = array(
        'title' => 'ERROR',
        'message' => '<h1>You should go eat some cookies while we\'re fixing this feature!</h1>',
      );
    }
    else {
      return new Response("<h1>FATAL ERROR</h1><pre>".var_export($e, true)."</pre>", 500);
    }

    return new Response($app['twig']->render('error.twig', $content), $e instanceof \Exception ? $e->getStatusCode() : null);
});

$app->before(function (Request $request) use ($app) {
  if (null === $app['session']->get('user') && !preg_match('#login#', $request->getPathInfo())) {
    // return new RedirectResponse($app['url_generator']->generate('app.login'));
  }
});

$app->after(function (Request $request, Response $response) use ($app) {
  $app['monolog']->addDebug("APP execute in " . (microtime(true) - APPLICATION_MICROTIME_START) . " secondes");
});
