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



$app->get('/configs/servers',         function () { $c = new Controller\Config(); return $c->Index_Action(); })->bind('configs.servers');
$app->get('/configs/servers/new',     function () { $c = new Controller\Config(); return $c->New_Action(); })->bind('configs.servers.new');
$app->post('/configs/save',           function () { $c = new Controller\Config(); return $c->Save_Action(); })->bind('configs.servers.save');
$app->get('/configs/edit/{ip}',       function () { $c = new Controller\Config(); return $c->Edit_Action(); })->bind('configs.servers.edit');
$app->post('/configs/update/{ip}',    function () { $c = new Controller\Config(); return $c->Update_Action(); })->bind('configs.servers.update');
$app->get('/configs/delete/{ip}',     function () { $c = new Controller\Config(); return $c->Delete_Action(); })->bind('configs.servers.delete');

$app->get('/configs/users',           function () { $c = new Controller\Config(); return $c->Index_Action(); })->bind('configs.users');



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
