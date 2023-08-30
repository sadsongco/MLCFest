<?php

require_once("../../../secure/mlc/db_connect.php");

foreach($_POST as $key=>$value) {
    if ($key != "submit") {
        $params[$key] = $value;
    }
}

try {
    $query = "INSERT INTO Artists VALUES (0, :name, :bio, :instagram, :twitter, :threads, :facebook);";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
}
catch(PDOException $e) {
    echo $e->getmessage();
}
header ('HX-Trigger:clearArtistForm');
header ('HX-Trigger-After-Settle:newArtist');

require_once("../../../secure/mlc/db_disconnect.php");

?>