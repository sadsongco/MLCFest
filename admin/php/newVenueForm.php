<?php

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

function getVenue($venue_id) {
    require_once("../../../secure/mlc/db_connect.php");
    $query = "SELECT * FROM Venues WHERE venue_id=?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$venue_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    require_once("../../../secure/mlc/db_disconnect.php");
    return $result[0];
}

$params = ["city"=>"Bristol"];
if (isset($_GET) && sizeof($_GET) > 0 && $_GET['edit']) {
    $params = getVenue($_GET['edit']);
    $params['update'] = true;
}

echo $m->render("newVenueForm", $params);

?>