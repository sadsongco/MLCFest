<?php

require_once("../../../secure/mlc/db_connect.php");

foreach($_POST as $key=>$value) {
    if ($key != "showUpdate") {
        $params[$key] = $value;
    }
}

try {
    $query = "UPDATE SHOWS
    SET venue=:venue, artist=:artist, start_time=:start_time, end_time=:end_time, notes=:notes
    WHERE show_id=:show_id;";
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