<?php 

require_once __DIR__.'/silex.phar';

$app = new Silex\Application();

# Register
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
));


# Routes
$app->get('/', function () use($app) {
    return $app['twig']->render('index/index.html.php', array());
});

$app->get('/servers_{servername}', function ($servername) use ($app) {
    $servername = $app['request']->get('servername');
    return $app['twig']->render('servers/details.html.php', array());
});


$app->get('/configs', function () use ($app) {
    return $app['twig']->render('configs/index.html.php', array());
});

# Run
$app->run();