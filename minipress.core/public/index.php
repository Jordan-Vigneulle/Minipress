<?php

if (PHP_SAPI == 'cli-server') {
    // Résout le problème d'affichage des fichiers css d'après :
    // https://www.php.net/manual/fr/features.commandline.webserver.php
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require_once __DIR__ . '/../src/vendor/autoload.php';
/* application boostrap */
$app = require_once __DIR__ . '/../src/conf/bootstrap.php';
$app->run();