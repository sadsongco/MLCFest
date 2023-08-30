<?php

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));



// $content = ["content"=>"STUFF"];

// echo $m->render("venues");

// echo $m->render("artists");

echo $m->render("outline");

?>