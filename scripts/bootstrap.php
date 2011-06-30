<?php 

require_once __DIR__.'/vendor/silex.phar';

$app = new Silex\Application();

# Ne peut être éxécuté que par un jobcron ?