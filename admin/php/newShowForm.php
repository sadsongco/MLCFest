<?php

require_once("../../../secure/mlc/db_connect.php");

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

function fillParameterArray($id_name, $table, $db) {
    $query = "SELECT $id_name, name FROM $table;";
    
    $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $params = [];
    
    foreach($result as $row) {
        $params[] = ["id"=>$row[$id_name], "name"=>$row['name']];
    }
    return $params;
}

$artists = fillParameterArray("artist_id", "Artists", $db);
$venues = fillParameterArray("venue_id", "Venues", $db);

echo $m->render("newShowForm", ["artists"=>$artists, "venues"=>$venues]);


require_once("../../../secure/mlc/db_disconnect.php");

?>