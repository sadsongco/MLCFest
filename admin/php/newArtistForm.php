<?php

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

function getArtist($artist_id) {
    require_once("../../../secure/mlc/db_connect.php");
    $query = "SELECT * FROM Artists WHERE artist_id=?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$artist_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    require_once("../../../secure/mlc/db_disconnect.php");
    return $result[0];
}

$params = [];
if (isset($_GET) && sizeof($_GET) > 0 && $_GET['edit']) {
    $params = getArtist($_GET['edit']);
    $params['update'] = true;
}
echo $m->render("newArtistForm", $params);

?>