<?php

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

function getParams() {
    require_once("../../../secure/mlc/db_connect.php");
    $query = "SELECT * FROM Params ORDER BY param_name DESC;";
    $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    require_once("../../../secure/mlc/db_disconnect.php");
    return $result;
}

$db_params = getParams();
$params = ["params"=>[]];

foreach ($db_params as $db_param) {
    if ($db_param["param_name"] == "festivalStart") $db_param["heading"] = "Festival Doors Open";
    if ($db_param["param_name"] == "festivalEnd") $db_param["heading"] = "Festival Ends";
    $params["params"][] = $db_param;
}

// echo "<pre>"; print_r($params); echo "</pre>";

echo $m->render("newParamsForm", $params);

?>