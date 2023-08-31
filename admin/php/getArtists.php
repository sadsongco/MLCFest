<?php

require_once("../../../secure/mlc/db_connect.php");

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

$query = "SELECT * FROM Artists;";

$result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

echo $m->render("artistsList", ["artists"=>$result]);

require_once("../../../secure/mlc/db_disconnect.php");

?>