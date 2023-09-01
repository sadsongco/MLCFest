<?php

require_once("../../../secure/mlc/db_connect.php");

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

function fillParameterArray($id_name, $table, $db, $show) {
    $query = "SELECT $id_name"."_id".", name FROM $table;";
    
    $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $params = [];
    
    foreach($result as $row) {
        $param_arr = ["id"=>$row[$id_name."_id"], "name"=>$row['name']];
        if (isset($show[$id_name]) && $show[$id_name] == $row[$id_name."_id"]) {
            $param_arr["selected"] = "selected";
        }
        $params[] = $param_arr;
    }
    return $params;
}

function getShow($id, $db) {
    $query = "SELECT * FROM Shows WHERE show_id=?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0];
}

$show = ["start_time"=>"2023-08-30T16:00", "end_time"=>"2023-08-30T16:00"];
if (isset($_GET) && sizeof($_GET) > 0 && $_GET['edit']) {
    $show = getShow($_GET['edit'], $db);
    $show["update"] = true;
}

$artists = fillParameterArray("artist", "Artists", $db, $show);
$venues = fillParameterArray("venue", "Venues", $db, $show);

echo $m->render("newShowForm", ["artists"=>$artists, "venues"=>$venues, "show"=>$show]);

require_once("../../../secure/mlc/db_disconnect.php");

?>