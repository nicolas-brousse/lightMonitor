<?php

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



$app->get('/settings/servers',         function () { $c = new Controller\Setting(); return $c->Index_Action(); })->bind('settings.servers');
$app->get('/settings/servers/new',     function () { $c = new Controller\Setting(); return $c->New_Action(); })->bind('settings.servers.new');
$app->post('/settings/servers/save',   function () { $c = new Controller\Setting(); return $c->Save_Action(); })->bind('settings.servers.save');
$app->get('/settings/edit/{ip}',       function () { $c = new Controller\Setting(); return $c->Edit_Action(); })->bind('settings.servers.edit');
$app->post('/settings/update/{ip}',    function () { $c = new Controller\Setting(); return $c->Update_Action(); })->bind('settings.servers.update');
$app->get('/settings/delete/{ip}',     function () { $c = new Controller\Setting(); return $c->Delete_Action(); })->bind('settings.servers.delete');

$app->get('/settings/users',           function () { $c = new Controller\Setting(); return $c->Index_Action(); })->bind('settings.users');


/** SESSION
$app->get('/login', function () use ($app) {
    $username = $app['request']->server->get('PHP_AUTH_USER', false);
    $password = $app['request']->server->get('PHP_AUTH_PW');

    if ('igor' === $username && 'password' === $password) {
        $app['session']->set('user', array('username' => $username));
        return $app->redirect('/account');
    }

    $response = new Response();
    $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'site_login'));
    $response->setStatusCode(401, 'Please sign in.');
    return $response;
});

$app->get('/account', function () use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }

    return "Welcome {$user['username']}!";
});
**/


/**
 * Errors and Favicon
 */

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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


$app->error(function(\Exception $e) use ($app) {
    if ($e instanceof NotFoundHttpException) {
        $content = vsprintf('<h1>Error %d</h1> <p>%s (%s)</p>', array(
           $e->getStatusCode(),
           Response::$statusTexts[$e->getStatusCode()],
           $app['request']->getRequestUri()
        ));
        return new Response($content, $e->getStatusCode());
    }
 
    if ($e instanceof HttpException) {
        return new Response('<h1>You should go eat some cookies while we\'re fixing this feature!</h1>', $e->getStatusCode());
    }
});
