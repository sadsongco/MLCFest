<?php

// echo "<pre>";
// echo "addShow";
// print_r($_POST);

require_once("../../../secure/mlc/db_connect.php");

foreach($_POST as $key=>$value) {
    if ($key != "submit") {
        if ($key == "start_time" || $key == "end_time") {$value = date("Y-m-d H:i:s", strtotime($value));}
        $params[$key] = $value;
    }
}

// print_r($params);
// echo "</pre>";
try {
    $query = "INSERT INTO Shows VALUES (0, :venue, :artist, :start_time, :end_time, :notes);";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
}
catch(PDOException $e) {
    echo $e->getmessage();
}
header ('HX-Trigger:clearShowForm');
header ('HX-Trigger-After-Settle:newShow');

require_once("../../../secure/mlc/db_disconnect.php");

?>